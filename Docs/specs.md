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


## Features

- Development plans
  - [ ] Create a new development plan
  - [ ] Edit a development plan
  - [ ] List all development plans
  - [ ] Remove a development plan
  - Root aggregate: yes

- Training sessions
  - [ ] Create a new training session for a selected development plan
  - [ ] Edit a training session
  - [ ] List all training sessions that belongs to a selected development plan
  - [ ] Remove a training session from a selected development plan
  - Root aggregate: no. A training session makes no sense without a development plan.

- Exercises
  - [ ] Create a new exercise
  - [ ] Edit an exercise
  - [ ] List all exercises
  - [ ] Remove an exercise from a selected training session
  - Root aggregate: yes. Exercises are shareable activities that can be used in multiple training sessions.

- Clubs
  - [ ] Create a new club
  - [ ] Edit a club
  - [ ] List all clubs
  - [ ] Remove a club
  - Root aggregate: yes

- Students
  - [ ] Create a new student
  - [ ] Edit a student
  - [ ] List all students
  - [ ] Remove a student
  - Root aggregate: yes

## Repositories

A repository should be created for an entity, if the entity can function on its own.




category: 
  - id
  - name
  - parent_category_id
  - is_active
  - created_at
  - updated_at

CREATE TABLE category (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255) NOT NULL,
parent_category_id INT,
is_active BOOLEAN NOT NULL DEFAULT true,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (parent_category_id) REFERENCES category(id)
);


SELECT 
    c1.*
FROM 
    category c1
LEFT JOIN 
    category c2 on c2.id = c1.parent_category_id
ORDER BY COALESCE(c2.id, c1.id), c1.id;



INSERT INTO category (name, parent_category_id) VALUES
('Car', NULL), -- Parent category
('Animal', NULL) -- Parent category;



INSERT INTO category (name, parent_category_id) VALUES
('Ford', 1), -- Sub-category under 'SUV'
('Chevrolet', 1), -- Sub-category under 'SUV'
('Labrador', 2), -- Sub-category under 'Dog'
('Persian', 2); -- Sub-category under 'Cat';




INSERT INTO category (name, parent_category_id) VALUES
('Car', NULL), -- Parent category
('Animal', NULL), -- Parent category
('SUV', 1), -- Child category under 'Car'
('Truck', 1), -- Child category under 'Car'
('Dog', 2), -- Child category under 'Animal'
('Cat', 2); -- Child category under 'Animal'



INSERT INTO category (name, parent_category_id) VALUES
('Ford', 3), -- Sub-category under 'SUV'
('Chevrolet', 3), -- Sub-category under 'SUV'
('Labrador', 5), -- Sub-category under 'Dog'
('Persian', 6); -- Sub-category under 'Cat'

