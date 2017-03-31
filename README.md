<h1> Project installation</h1>
<ol>
	<li>
		<p>Install symfonty using composer</p>
		<pre>composer create-project symfony/framework-standard-edition project "3.2.6"</pre>
	</li>
	<li>
		<p>Add google apiclient dependency to composer.json</p>
		<i>
		<pre>
"require": {
	....
	"google/apiclient": "2.0"
}
		</pre>
		</i>
	</li>
	<li>
		<p>Remove composer.lock</p>
	</li>
	<li>
		<p>Install google apiclient</p>
		<i><pre>composer install</pre></i>
	</li>
	<li>
		<p>Replace directories: 'app', 'src', 'tests'</p>
	</li>
	<li>
		<p>Create database</p>
		<pre>php bin/console doctrine:database:create</pre>
	</li>
	<li>
		<p>Validate mappings. [Mapping] OK  are enough for this step</p>
		<pre>php bin/console doctrine:schema:validate</pre>
	</li>
	<li>
		<p>Create the Database Tables/Schema</p>
		<pre>php bin/console doctrine:schema:update --force</pre>
	</li>
	<li>
		<p>Update constraint (on delete cascade , on update cascade) either through console or phpmyadmin</p>
		<pre>ALTER TABLE `message` DROP FOREIGN KEY `constraint_name`; 
ALTER TABLE `message` ADD CONSTRAINT `constraint_name` FOREIGN KEY (`contact_id`) REFERENCES `contacts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;</pre>
	</li>	
	<li>
		<p>Setup your gmail accaut</p>
		<ul>
			<li>Open <a href="https://console.developers.google.com/start/api?id=gmail&hl=uk">wizzard</a></li>
			<li>Press "Go to credentials" -> "cancel" on "Add credentials to your project" form</li>
			<li>Open tab "OAuth consent screen" fill in "Product name shown to users" and save</li>
			<li>Open tab "Credentials" -> OAuth client ID -> Web application</li>
			<li>Open tab "Credentials" -> OAuth client ID -> Web application</li>
			<li>
				<p>Add Authorized redirect URIs:</p>
				<pre>
http://localhost:8000
http://localhost:8000/googleClientOAuth
				</pre>
				<p>-> create -> ok -> download JSON</p>
				<p>save as: <i>yourProjectFolder/app/config/google_client_secret.json</i></p>
			</li>			
		</ul>
	</li>
	<li>
		<p>Run dev server</p>
		<pre>php bin/console server:run</pre>
	</li>	
</ol>
<p>user: admin, password: helloworld</p>
