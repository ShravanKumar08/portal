@servers(['web' => 'deploy-technokryon-production', 'staging' => 'deploy-technokryon-staging'])

@task('deploy', ['on' => 'web', 'confirm' => true])
    cd ~/public_html/home_projects/wfm/
    git pull origin master
    php artisan migrate --force
@endtask

@story('staging', ['on' => 'staging'])
    application_staging
    webservice_staging
@endstory

@task('application_staging')
    cd /var/www/html/kanban/application
    git pull origin master
    npm i
    npm run build:prod
@endtask

@task('webservice_staging')
    cd /var/www/html/wfm
    git pull origin version-5.7
    php artisan migrate --force
@endtask
