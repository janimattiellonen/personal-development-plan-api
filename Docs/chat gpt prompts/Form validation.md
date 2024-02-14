Clubs:
    - name
        - string
        - min: 5
        - max: 255
        - required
    - isActive
        - boolean
        - optional

using Laravel's validation rules, please create a validation class for the Club model.


Students:
    - name
        - string
        - min: 5
        - max: 255
        - required
    - isActive
        - boolean
        - optional
    - firstName:
        - string
        - min: 2
        - max: 64
        - required
    - lastName: 
        - string
        - min: 2
        - max: 64
        - required
    - email        
        - string
        - min: 5
        - max: 255
        - email
        - required
    - password
        - string
        - min: 8
        - max: 255
        - required
    - type      
        - string
        - min: 2
        - max: 10
        - required
    - userRole  
        - string
        - min: 2
        - max: 10
        - required


using Laravel's validation rules, please create a validation class for the Student model.


