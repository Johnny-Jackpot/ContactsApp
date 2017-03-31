1. Install symfonty using composer
 
composer create-project symfony/framework-standard-edition project "3.2.6"

2. Add google apiclient dependency to composer.json
"require": {
	....
	"google/apiclient": "2.0"
}

3. Remove composer.lock
4. Install google apiclient 

composer install

5. Replace directories: 'app', 'src', 'tests'

6. Create database

php bin/console doctrine:database:create

7. Validate mappings

php bin/console doctrine:schema:validate

[Mapping] OK  are enough for this step

8. Create the Database Tables/Schema

php bin/console doctrine:schema:update --force


8.1 Update constraint (on delete cascade , on update cascade) either through console or phpmyadmin

SQL:

ALTER TABLE `message` DROP FOREIGN KEY `constraint_name`; 
ALTER TABLE `message` ADD CONSTRAINT `constraint_name` FOREIGN KEY (`contact_id`) REFERENCES `contacts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

9. Setting up your gmail accaut

9.1 Create gmail accaunt

9.2 Open this wizzard https://console.developers.google.com/start/api?id=gmail&hl=uk

9.3 Select "Create a project" and press continue

9.4 Press "Go to credentials"

9.5 Press "cancel" on "Add credentials to your project" form

9.6 Open tab "OAuth consent screen" fill in "Product name shown to users" and save

9.7 Open tab "Credentials" -> OAuth client ID -> Web application

9.8 add Authorized redirect URIs:

http://localhost:8000/googleClientOAuth  -> create -> ok -> download JSON

save as: yourProjectFolder/app/config/google_client_secret.json

10. run dev server 

php bin/console server:run

11. Log in

user: admin
password: helloworld
