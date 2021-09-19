### Requirements
* php >= 7.4
* ext-sqlite3

### Installation
```bash
git clone git@github.com:Arbagen/calendar-managment.git

bin/console doctrine:migrations:migrate

#create some segments
bin/console app:create-segments
#create random events
bin/console app:create-events
#option --with-name to create with name placeholder
bin/console app:create-events --with-name

php -S localhost:8888 -t public/

#to add name to subscriber
bin/console app:subscriber:add-name subscriberId subscriberName

#use ngrock to access from phone
./ngrok http -host-header=rewrite localhost:8888
```
[Go to browser](https://localhost:8888/)

Clear cookies to forget subscriber



