import { copyFile, mkdir, readdir, stat } from "node:fs/promises";
import path from "node:path";
import { fileURLToPath } from "node:url";

const scriptDir = path.dirname(fileURLToPath(import.meta.url));
const projectRoot = path.resolve(scriptDir, "..");
const themeSlug = path.basename(projectRoot);
const buildRoot = path.join(projectRoot, "build");
const outDir = path.join(buildRoot, themeSlug);
const COPY_TIMEOUT_MS = 5000;

const rootFilesToInclude = new Set(["style.css", "favicon.ico"]);
const rootDirsToInclude = [
  { name: "assets", skipLockedFiles: true },
  { name: "inc", skipLockedFiles: false },
  { name: "template-parts", skipLockedFiles: false },
];

function isLockError(error) {
  return ["EPERM", "EACCES", "EBUSY", "ETIMEDOUT", "ETIMEOUT"].includes(error?.code);
}

function createTimeoutError(srcPath, ms) {
  const error = new Error(`Timed out copying file after ${ms}ms: ${srcPath}`);
  error.code = "ETIMEOUT";
  return error;
}

async function copyFileWithTimeout(srcPath, destPath, timeoutMs = COPY_TIMEOUT_MS) {
  let timeoutId;
  try {
    await Promise.race([
      copyFile(srcPath, destPath),
      new Promise((_, reject) => {
        timeoutId = setTimeout(() => reject(createTimeoutError(srcPath, timeoutMs)), timeoutMs);
      }),
    ]);
  } finally {
    clearTimeout(timeoutId);
  }
}

async function pathExists(targetPath) {
  try {
    await stat(targetPath);
    return true;
  } catch {
    return false;
  }
}

async function copyRootPhpFiles() {
  const entries = await readdir(projectRoot, { withFileTypes: true });

  for (const entry of entries) {
    if (!entry.isFile()) {
      continue;
    }

    const isPhp = path.extname(entry.name).toLowerCase() === ".php";
    const isKnownFile = rootFilesToInclude.has(entry.name);

    if (!isPhp && !isKnownFile) {
      continue;
    }

    const srcPath = path.join(projectRoot, entry.name);
    const destPath = path.join(outDir, entry.name);
    await copyFileWithTimeout(srcPath, destPath);
  }
}

async function copyDirectoryRecursive(srcDir, destDir, options = { skipLockedFiles: false }) {
  await mkdir(destDir, { recursive: true });
  const entries = await readdir(srcDir, { withFileTypes: true });

  for (const entry of entries) {
    const srcPath = path.join(srcDir, entry.name);
    const destPath = path.join(destDir, entry.name);

    if (entry.isDirectory()) {
      await copyDirectoryRecursive(srcPath, destPath, options);
      continue;
    }

    if (entry.isFile()) {
      try {
        await copyFileWithTimeout(srcPath, destPath);
      } catch (error) {
        if (options.skipLockedFiles && isLockError(error)) {
          console.warn(`Skipped locked asset file: ${path.relative(projectRoot, srcPath)} (${error.code})`);
          continue;
        }
        throw error;
      }
    }
  }
}

async function copyThemeDirectories() {
  for (const dirConfig of rootDirsToInclude) {
    const dirName = dirConfig.name;
    const srcPath = path.join(projectRoot, dirName);
    const destPath = path.join(outDir, dirName);

    if (!(await pathExists(srcPath))) {
      continue;
    }

    await copyDirectoryRecursive(srcPath, destPath, {
      skipLockedFiles: !!dirConfig.skipLockedFiles,
    });
  }
}

async function main() {
  console.log("Packaging theme...");
  await mkdir(buildRoot, { recursive: true });
  await mkdir(outDir, { recursive: true });

  await copyRootPhpFiles();
  console.log("Copied root files.");
  await copyThemeDirectories();
  console.log("Copied theme directories.");

  console.log(`Theme package created: ${path.relative(projectRoot, outDir)}`);
}

main().catch((error) => {
  console.error("Theme packaging failed.");
  console.error(error);
  process.exitCode = 1;
});
