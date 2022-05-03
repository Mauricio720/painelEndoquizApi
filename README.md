# Painel E Api Endoquiz

Foi desenvolvido uma API junto com um painel administrativo para uma aplicação de criação de simulados para provas de endocrinologia. A tecnologia utlizada foi o laravel que tem a capacidade de criar um projeto com front-end utilizando o blade  (que é o painel administrativo) e api que é consumida pela aplicação de simulado desenvolvida em react http://app.endoquiz.com.br.


# Instalação
Para utilizar a api, primeiramente use o composer update ou composer install para criar a pasta vendor. Após isso importe o arquivo sql endoquiz.sql no seu banco de dados (Não fiz o uso de migrations por não conhecer ainda). Ao fazer isso, vá no arquivo .env.exemple e mude as configurações para as escolhidas por você. As principais são as citadas abaixo:

# ENV Configurações
### API_URL="url local ou da hospedagem" 
### APP_ENV=local ou developmente 
### DB_DATABASE=nome banco de dados 
### DB_USERNAME=usuario do banco de dados 
### DB_PASSWORD=senha do banco de dados

# Essa aplicação faz o uso de envio de emails, então precisa ser preenchido as informações de email no env
### MAIL_MAILER=smtp
### MAIL_HOST=smtp.mailtrap.io
### MAIL_PORT=2525
### MAIL_USERNAME=null
### MAIL_PASSWORD=null
### MAIL_ENCRYPTION=null
### MAIL_FROM_ADDRESS=null
### MAIL_FROM_NAME="${APP_NAME}"

#### Após preencher o arquivo env.example renomeie o arquivo para .env

Essa api utiliza o storage do laravel para upload de imagens, então caso você use localmente utilize o comando php artisan storage:link para criar o link simbólico na pasta public. Caso esteja usando em hospedagem e não tiver acesso ao promp de comando, use a rota "nomedoseudominio/foo" para criar o link simbólico.

Após essas configurações utilize o php artisan key:generate para criar um nova chave, agora basta usar o php artisan serve para iniciar a aplicação caso esteja local.

