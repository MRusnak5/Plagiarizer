> # Plagiarizer

----------

# Requirements
**PHP 7.3+**
    
    BCMATH PHP extension
    Ctype PHP extension
    Fileinfo PHP extension
    JSON PHP extension
    Mbstring PHP extension
    OpenSSL PHP extension
    PDO PHP extension
    Tokenizer PHP extension
    XML PHP extension

**Composer**

**Python 3.5+**
    
    sys package
    json package
    pandas package -pip install -U pandas
    sklearn.metrics.pairwise.cosine_similarity package - pip install -U scikit-learn

## Installation

Clone the repository

    git clone https://github.com/MRusnak5/Plagiarizer.git

Switch to the repository folder

    cd plagiarizer

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env
(**Set the database connection in .env before migrating**)
    Database connection ending with _SECOND have to point to Moodle Database!!!

Generate a new application key

    php artisan key:generate

Clear cache files
    
    php artisan config:cache
    
Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate fresh --seed

Start the local development server

    php artisan serve

You can now access the server at http://127.0.0.1:8000

Default Login credentials:

    admin@admin.com
    password
