# wordpress-react-theme-development
Wordpress, React, Docker theme development (from scratch) setup. 
Full step-by-step tutorial is here: https://sammuir.co.nz/blog/wordpress-react-tutorial/

Prerequisites: Docker Desktop, Docker Compose (should have been included with Docker on Windows and macOS) & VSCode installed.

(thanks https://developer.wordpress.com/2022/11/14/seetup-local-development-environment-for-wordpress/ for the bulk of help with this setup).

To run: 
  docker-compose up -d

Docker will create a ‘wordpress/’ directory that alongside your docker-compose.yml file, which is where your WordPress install will be bound to. 

WordPress site will be located on localhost:8080
phpMyAdmin will be located on localhost:8180

This next step is necessary due to how Docker handles file permissions and how the wordpress Docker image is constructed. 

Navigate to your project directory by doing the following: 
  docker exec -it <container_id> /bin/bash 

And then run the following command: 
  chown -R {your-username}:{your-username} /var/www/html 
  chown -R root:root /var/www/html

This will change the owner and group of all files and directories in the ‘/var/www/html’ directory to ‘root’ which is the user and group that the web server runs. 
After this, navigate to localhost:8080 in your browser and you should be greeted with the standard WordPress installation screen. 
