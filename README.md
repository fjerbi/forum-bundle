 ### Execute this command in your terminal
 `composer require fjerbi/blog-bundle`

### Add these lines in your services.yaml
```
 fjerbi\BlogBundle\Controller\DefaultController:
        calls:
            - method: setContainer
              arguments: [ '@service_container' ]
```

### Add this in your routes.yaml
``` 
blog:
  resource: '@BlogBundle/Controller/'
  type: annotation
  prefix: /blog 
  
  ```

### And finally execute this command
   ` php bin/console doctrine:schema:update --force `
   
 ##### check your database if the new tables were added successfully

#### NOTES: if you want to check the routes just execute this command
` php bin/console debug:router `
  
# What's included ?
- Responsive design
- CRUD
- Search Post
- Responsive admin dashboard
- Tags and categories

# Coming soon: Updates
- Image upload
- Paginations
- Comments section
- Crud on categories
- Full dashboard stastics 
