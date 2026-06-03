// =======================================================================
//  Vite config — Cactusman Portfolio
//
//  Production build outputs to /assets/build/, consumed by inc/enqueue.php.
//  Dev mode runs an HMR server on http://localhost:5173 — toggle by adding
//  `define( 'CMP_DEV', true );` to your wp-config.php.
// =======================================================================

import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
	root: '.',

	server: {
		host:   '0.0.0.0',
		port:   5173,
		strictPort: true,
		cors:   true, // allow WordPress to load assets cross-origin in dev
		origin: 'http://localhost:5173',
		hmr: {
			host: 'localhost',
		},
	},

	build: {
		outDir:        'assets/build',
		emptyOutDir:   true,
		manifest:      false,
		cssCodeSplit:  false,
		assetsInlineLimit: 0,
		rollupOptions: {
			input: resolve(__dirname, 'src/js/main.js'),
			output: {
				entryFileNames: 'main.js',
				assetFileNames: ({ name }) => {
					if (name && name.endsWith('.css')) return 'main.css';
					return 'assets/[name][extname]';
				},
				chunkFileNames: 'chunks/[name].[hash].js',
			},
		},
	},

	css: {
		preprocessorOptions: {
			scss: {
				api: 'modern-compiler',
			},
		},
	},
});
