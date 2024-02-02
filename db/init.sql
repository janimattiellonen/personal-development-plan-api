CREATE TABLE profiles (
    id INT AUTO_INCREMENT,
    age TINYINT(3) NULL,

    PRIMARY KEY (id)

)


CREATE TABLE exercises (
    id INT AUTO_INCREMENT,
    name VARCHAR(64) NOT NULL,
    description VARCHAR(64) NOT NULL,
    instructions TEXT NOT NULL,
    is_active BOOLEAN NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT,
    name VARCHAR(64) NOT NULL,
    is_active BOOLEAN NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE exercises_categories (
    exercise_id INT,
    category_id INT,
    PRIMARY KEY (exercise_id, category_id),
    FOREIGN KEY (exercise_id) REFERENCES exercises(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

create table clubs (
   id INT AUTO_INCREMENT,
   name varchar(255) NOT NULL,
   PRIMARY KEY (id)
);

CREATE TABLE students_clubs (
    student_id INT NOT NULL,
    club_id INT NOT NULL,
    PRIMARY KEY (student_id, club_id),
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (club_id) REFERENCES clubs(id)
);

CREATE TABLE training_sessions (
    id INT AUTO_INCREMENT,
    started_at DATETIME NOT NULL,
    finished_at DATETIME,
    is_active BOOLEAN NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE training_sessions_exercises (
    id INT AUTO_INCREMENT,
    training_session_id INT,
    exercise_id INT,
    FOREIGN KEY (training_session_id) REFERENCES training_sessions(id),
    FOREIGN KEY (exercise_id) REFERENCES exercises(id),
    PRIMARY KEY (id)
);

