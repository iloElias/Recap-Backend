# Use a imagem oficial do PHP com Apache
FROM php:8.1-apache

# Atualize os pacotes e instale as dependências necessárias
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install zip

# Ative os módulos do Apache necessários para reescrever URLs
RUN a2enmod rewrite

# Defina o ServerName globalmente para suprimir o aviso do Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copie os arquivos do seu aplicativo para o contêiner
COPY . /var/www/html

# Copie o arquivo .htaccess para o diretório raiz do Apache
COPY .htaccess /var/www/html/.htaccess

# Exponha a porta 80 (a porta padrão do Apache)
EXPOSE 80

# Comando para iniciar o Apache quando o contêiner for iniciado
CMD ["apache2-foreground"]
