# Domain
 
## Models:

- Instructor
- Student
- Category
- DevelopmentPlan
- Exercise
- TrainingSession


- Exercise
  - id
  - name
  - description
  - instructions
  - url
  - youtube_url
  - is_active
  - created_at
  - updated_at



- TrainingSession
  - id
  - started_at
  - finished_at
  - is_active
  - created_at
  - updated_at





## Migrations

### Create new migration

``
php artisan make:migration create_clubs_table
``

### Create new seeder

``
php artisan make:seeder UserSeeder
``

### Execute seeder

``
php artisan db:seed --class=UserSeeder
``

### Permissions

By default, an instructor may only add students that belong to the same club.

The instructor may also send invitations to people by e-mail to be part of a 
personal development plan.

This way, we minimize the access to young players.

A "super admin" may see a broader list of users.


