<?php
/**
 * Dynamic Course Detail Page
 * course-detail.php
 * Optimized for SEO, AEO (Featured Snippets), and GEO (AI Citations)
 */

$slug = trim($_GET['slug'] ?? '');

$courses = [
    'python-training-kanpur' => [
        'slug' => 'python-training-kanpur',
        'title' => 'Python Course in Kanpur with Placement | LogixCode',
        'meta_description' => 'Join LogixCode\'s Python course in Kanpur with live projects & 100% placement support. Master Django, OOP, APIs & scripting. Book your free demo class today!',
        'h1' => 'Python Training Course in Kanpur with Live Projects & Placement',
        'badge' => 'High Placement Demand',
        'answer_first' => 'LogixCode offers the premier Python training course in Kanpur at Swarn Jayanti Vihar, Koyla Nagar. Spanning 4 to 6 months, this program covers Python programming, Django web framework, REST APIs, and database integration. Fees start from ₹5,000 for vocational batches up to ₹25,000 for 6-month apprenticeships with 100% placement assistance.',
        'duration' => '4 to 6 Months (Flexible Weekday & Weekend Batches)',
        'mode' => 'Classroom (Kanpur Campus) & Live Online Interactive',
        'fees' => '₹5,000 (Summer/Vocational) to ₹25,000 (Apprenticeship)',
        'eligibility' => 'B.Tech / BCA / MCA / Diploma students and IT career switchers (No prior coding required)',
        'certificate' => 'ISO Verified Certificate + Live Project Completion Letter',
        'trainer' => [
            'name' => 'Ashutosh Tiwari',
            'role' => 'Lead Full-Stack & Python Architect',
            'experience' => '8+ Years Industry Experience',
            'bio' => 'Senior software engineer and tech educator specializing in Python, scalable web architectures, and cloud deployment.'
        ],
        'tools' => ['Python 3.12', 'Django', 'Flask', 'PostgreSQL', 'SQLite', 'REST Framework', 'Git & GitHub', 'Postman'],
        'syllabus' => [
            [
                'module' => 'Module 1: Python Fundamentals & Data Structures',
                'topics' => ['Syntax, variables, datatypes & operators', 'Control flow: if-else, loops & list comprehensions', 'Functions, lambda expressions & modular programming', 'Core data structures: Lists, Tuples, Dictionaries & Sets']
            ],
            [
                'module' => 'Module 2: Object-Oriented Programming (OOP) & Advanced Python',
                'topics' => ['Classes, objects, constructors & inheritance', 'Polymorphism, encapsulation & abstraction', 'Exception handling & file I/O operations', 'Working with external libraries & Virtual Environments']
            ],
            [
                'module' => 'Module 3: Database Integration & SQL',
                'topics' => ['Relational database design concepts', 'SQL queries: CRUD, Joins, Aggregations', 'Connecting Python with SQLite and PostgreSQL', 'ORM (Object Relational Mapping) basics']
            ],
            [
                'module' => 'Module 4: Web Development with Django & REST APIs',
                'topics' => ['MVT architecture and Django project structure', 'Django models, views, templates & forms', 'Building RESTful APIs with Django REST Framework', 'Authentication, JWT tokens & user authorization']
            ],
            [
                'module' => 'Module 5: Capstone Live Project & Deployment',
                'topics' => ['End-to-end full-stack web application development', 'Version control with Git & GitHub workflow', 'Deployment on cloud platforms (AWS / Render / DigitalOcean)', 'Interview preparation & resume building sessions']
            ]
        ],
        'projects' => [
            'Enterprise Job & Internship Recruitment Portal',
            'E-commerce RESTful API with Payment Gateway Integration',
            'Real-Time Student Attendance & Performance Tracking System'
        ],
        'faqs' => [
            [
                'q' => 'Python course kitne mahine ka hota hai?',
                'a' => 'LogixCode Kanpur mein Python training course 4 se 6 mahine ka hota hai. Ismein 2 mahine core & advanced programming, 2 mahine Django web framework & database, aur aakhri mahine live capstone project aur interview preparation hota hai.'
            ],
            [
                'q' => 'Kanpur mein Python course ki fees kitni hai?',
                'a' => 'LogixCode mein short-term summer ya vocational Python training ₹5,000 se shuru hoti hai. Comprehensive 6-month industry apprenticeship program with 100% placement support ki fee ₹25,000 hai. Installment options bhi uplabdh hain.'
            ],
            [
                'q' => 'Kya Python seekhne ke liye pehle se coding aana zaroori hai?',
                'a' => 'Nahi, koi pehle ki programming experience zaroori nahi hai. Course bilkul basic level se shuru hota hai jismein logic building aur problem-solving step-by-step sikhaya jata hai.'
            ],
            [
                'q' => 'Does LogixCode provide 100% placement assistance in Kanpur?',
                'a' => 'Yes, LogixCode provides 100% dedicated placement support. Hum resume building, mock interviews conduct karte hain aur Kanpur, Lucknow, Noida aur pan-India 100+ hiring partners ke saath interviews schedule karate hain.'
            ],
            [
                'q' => 'Kya main admission lene se pehle demo class le sakta hoon?',
                'a' => 'Haan! LogixCode Kanpur campus par 1 free demo session provide karta hai. Aap online form submit karke ya WhatsApp (+91 8467898854) par message karke apna demo book kar sakte hain.'
            ],
            [
                'q' => 'Kanpur campus ka batch timing kya hai?',
                'a' => 'Hamare paas morning (9:00 AM), afternoon (1:00 PM), aur evening (5:00 PM) batches hain. College students aur working professionals ke liye special weekend batches bhi available hain.'
            ]
        ]
    ],
    'full-stack-web-development-kanpur' => [
        'slug' => 'full-stack-web-development-kanpur',
        'title' => 'Full Stack Course in Kanpur with Placement | LogixCode',
        'meta_description' => 'Best Full Stack Web Development course in Kanpur covering React, Node.js, Express & MongoDB. Build live apps with 100% placement assistance. Apply today!',
        'h1' => 'Full Stack Web Development Course in Kanpur (MERN Stack)',
        'badge' => 'Highest Industry Hiring',
        'answer_first' => 'LogixCode\'s Full Stack Web Development course in Kanpur provides exhaustive hands-on training in the MERN stack (MongoDB, Express.js, React.js, and Node.js). Delivered over 4 to 6 months at our Kanpur center, the course equips learners to construct scalable full-stack applications with real-world deployments and assured placement support.',
        'duration' => '4 to 6 Months (Weekday & Weekend Sessions)',
        'mode' => 'Classroom (Kanpur Campus) & Live Online Interactive',
        'fees' => '₹5,000 (Foundation) to ₹25,000 (Complete MERN Apprenticeship)',
        'eligibility' => 'Students of BCA, B.Tech, MCA, graduates, and aspiring frontend/backend engineers.',
        'certificate' => 'Certified Full Stack Web Developer Credentials + Project Letter',
        'trainer' => [
            'name' => 'Ashutosh Tiwari',
            'role' => 'Lead Full-Stack & Systems Trainer',
            'experience' => '8+ Years Engineering Experience',
            'bio' => 'Full-stack specialist with proven expertise in building modern enterprise web applications and mentoring 500+ successful engineers.'
        ],
        'tools' => ['HTML5 & CSS3', 'Tailwind CSS', 'JavaScript ES6+', 'React.js', 'Node.js', 'Express.js', 'MongoDB', 'Git / GitHub', 'Docker'],
        'syllabus' => [
            [
                'module' => 'Module 1: Modern Frontend Architecture',
                'topics' => ['Semantic HTML5, CSS3, Flexbox & CSS Grid', 'Responsive design with Tailwind CSS', 'Modern JavaScript (ES6+): Closures, Promises & Async/Await', 'DOM manipulation & Browser APIs']
            ],
            [
                'module' => 'Module 2: React.js Component Engineering',
                'topics' => ['JSX, components, props & state management', 'React Hooks: useState, useEffect, useMemo, useRef', 'React Router for dynamic Single Page Applications (SPAs)', 'State management with Redux Toolkit / Context API']
            ],
            [
                'module' => 'Module 3: Backend Development with Node.js & Express',
                'topics' => ['Node.js runtime, event loop, and asynchronous architecture', 'RESTful API creation with Express.js', 'Middleware design, error handling & input validation', 'JWT-based authentication and secure role-based access']
            ],
            [
                'module' => 'Module 4: Database Engineering with MongoDB',
                'topics' => ['NoSQL schema design & data modeling with Mongoose', 'CRUD queries, indexing, and aggregation pipelines', 'Data relations and population techniques', 'Database security best practices']
            ],
            [
                'module' => 'Module 5: Full-Stack Integration & Production Deployment',
                'topics' => ['Connecting React frontend with Node/Express APIs', 'Cloud database hosting on MongoDB Atlas', 'Deployment on Vercel, Render & AWS EC2', 'Resume preparation, GitHub portfolio, and technical mock interviews']
            ]
        ],
        'projects' => [
            'Complete SaaS Billing & Subscription Management Application',
            'Real-Time Chat & Collaboration Platform using Socket.io',
            'Full-Featured Multi-Vendor E-Commerce Platform'
        ],
        'faqs' => [
            [
                'q' => 'Kanpur mein Full Stack Developer course kitne time ka hota hai?',
                'a' => 'LogixCode mein Full Stack (MERN) course 4 se 6 mahine ka hota hai. Ismein Frontend (HTML, CSS, Tailwind, React), Backend (Node, Express), Database (MongoDB), aur live projects thoroughly cover hote hain.'
            ],
            [
                'q' => 'Full stack development course ki fees kya hai?',
                'a' => 'Foundation web development batches ₹5,000 se start hote hain, jabki complete 6-month MERN Stack Apprenticeship with guaranteed placement support ₹25,000 mein uplabdh hai.'
            ],
            [
                'q' => 'Kya non-CS students ye course kar sakte hain?',
                'a' => 'Bilkul. Course ka syllabus zero programming background ko dhyan mein rakh kar design kiya gaya hai. Hum basic HTML aur programming fundamentals se start karte hain.'
            ],
            [
                'q' => 'Kya course completion ke baad certificate aur placement support milta hai?',
                'a' => 'Haan, complete live projects ke baad industry-recognized certificate milta hai aur hamari placement team mock interviews aur campus hiring sessions organize karti hai.'
            ],
            [
                'q' => 'Kanpur mein institute kahan par sthit hai?',
                'a' => 'LogixCode Institute Shri Balaji Chauraha, 2/1 Koyla Nagar, Swarn Jayanti Vihar, Kanpur mein sthit hai, jahan offline labs aur AC classrooms available hain.'
            ]
        ]
    ],
    'java-programming-kanpur' => [
        'slug' => 'java-programming-kanpur',
        'title' => 'Java Training in Kanpur with Spring Boot | LogixCode',
        'meta_description' => 'Master Java programming in Kanpur with Spring Boot, Hibernate, microservices & live enterprise projects. 100% placement support. Enroll at LogixCode now!',
        'h1' => 'Core & Advanced Java Training in Kanpur (Spring Boot & Microservices)',
        'badge' => 'Enterprise Standard',
        'answer_first' => 'LogixCode provides industry-aligned Java training in Kanpur covering Core Java, OOPs, Collections, multithreading, Spring Boot, Hibernate, and Microservices. Designed for aspiring backend software developers, this 4 to 6-month hands-on course prepares students for top product and service MNC hiring drives.',
        'duration' => '4 to 6 Months',
        'mode' => 'Classroom (Kanpur Campus) & Live Online',
        'fees' => '₹5,000 (Core Java) to ₹25,000 (Full Java Enterprise)',
        'eligibility' => 'B.Tech, BCA, MCA students and working professionals aiming for enterprise backend roles.',
        'certificate' => 'Certified Java Enterprise Developer Certificate',
        'trainer' => [
            'name' => 'Ashutosh Tiwari',
            'role' => 'Enterprise Architecture Mentor',
            'experience' => '8+ Years Experience',
            'bio' => 'Enterprise Java and backend systems engineer with in-depth knowledge of Spring Boot, distributed databases, and high-performance APIs.'
        ],
        'tools' => ['Java 21', 'Spring Boot 3', 'Hibernate / JPA', 'MySQL', 'PostgreSQL', 'Maven', 'IntelliJ IDEA', 'Docker'],
        'syllabus' => [
            [
                'module' => 'Module 1: Core Java & Object-Oriented Principles',
                'topics' => ['Java syntax, JVM, JRE & JDK architecture', 'Classes, objects, inheritance, polymorphism & interfaces', 'Exception handling & memory management', 'Java Collections Framework (List, Set, Map, Queue)']
            ],
            [
                'module' => 'Module 2: Advanced Java & Database Connectivity',
                'topics' => ['Multithreading, concurrency & thread synchronization', 'Java Generics, Streams API & Lambda expressions', 'JDBC API for database transactions', 'Design patterns (Singleton, Factory, MVC)']
            ],
            [
                'module' => 'Module 3: Spring Framework & Spring Boot',
                'topics' => ['Inversion of Control (IoC) & Dependency Injection', 'Spring Boot auto-configuration & starters', 'Building REST APIs with Spring Web', 'Validation, exception handling & Swagger documentation']
            ],
            [
                'module' => 'Module 4: Spring Data JPA & Microservices',
                'topics' => ['Hibernate ORM & Spring Data JPA repositories', 'Database relationships (@OneToMany, @ManyToMany)', 'Microservices fundamentals & inter-service communication', 'Security with Spring Security and JWT tokens']
            ]
        ],
        'projects' => [
            'Banking & Financial Transaction Management API',
            'Hospital Management Enterprise System with Microservices Architecture',
            'High-Throughput Inventory Management REST Service'
        ],
        'faqs' => [
            [
                'q' => 'Kanpur mein Java training institute kaunsa best hai?',
                'a' => 'LogixCode Kanpur ka leading Java training center hai jahan enterprise-level Spring Boot aur Microservices par live project training di jaati hai.'
            ],
            [
                'q' => 'Java course mein placement ka kya process hai?',
                'a' => 'Hum data structures, algorithms aur Spring Boot interviews ke liye dedicated mock rounds conduct karte hain aur leading IT companies ke saath placement drives karwate hain.'
            ],
            [
                'q' => 'Kya Spring Boot is course mein shamil hai?',
                'a' => 'Haan, hamare full Java curriculum mein Spring Boot 3, Spring Data JPA, Spring Security, aur Microservices thoroughly sikhaye jaate hain.'
            ]
        ]
    ],
    'data-science-machine-learning-kanpur' => [
        'slug' => 'data-science-machine-learning-kanpur',
        'title' => 'Data Science Course in Kanpur with AI & ML | LogixCode',
        'meta_description' => 'Advance your career with LogixCode\'s Data Science course in Kanpur. Learn Python, Pandas, Machine Learning & Power BI with live datasets. Book a free demo!',
        'h1' => 'Data Science & Machine Learning Course in Kanpur with AI',
        'badge' => 'Highest Growth Track',
        'answer_first' => 'LogixCode provides an in-depth Data Science and Machine Learning program in Kanpur covering Python data analytics, NumPy, Pandas, Scikit-Learn, data visualization, and predictive AI models. Over 4 to 6 months, learners gain practical proficiency working on live datasets, Power BI dashboards, and enterprise ML pipelines.',
        'duration' => '4 to 6 Months',
        'mode' => 'Classroom (Kanpur Campus) & Live Online',
        'fees' => '₹5,000 (Data Analytics) to ₹25,000 (Full Data Science & ML Track)',
        'eligibility' => 'Graduates, engineering students, BCA/MCA, and mathematics/statistics backgrounds.',
        'certificate' => 'Data Science & Applied AI Specialist Certificate',
        'trainer' => [
            'name' => 'Ashutosh Tiwari',
            'role' => 'Data Science & AI Lead Instructor',
            'experience' => '8+ Years Industry Experience',
            'bio' => 'Hands-on practitioner in applied machine learning, exploratory data analysis, and predictive models.'
        ],
        'tools' => ['Python', 'Pandas', 'NumPy', 'Matplotlib & Seaborn', 'Scikit-Learn', 'Power BI', 'Jupyter', 'SQL'],
        'syllabus' => [
            [
                'module' => 'Module 1: Python for Data Science & Numerical Computing',
                'topics' => ['Python foundations for scientific computation', 'NumPy arrays, mathematical operations & indexing', 'Pandas Series, DataFrames, data cleaning & wrangling', 'Handling missing data, outliers & feature transformation']
            ],
            [
                'module' => 'Module 2: Exploratory Data Analysis & Visualizations',
                'topics' => ['Statistical distributions, correlations & hypothesis testing', 'Data visual storytelling with Matplotlib & Seaborn', 'Interactive dashboards with Power BI', 'SQL queries for data analysts']
            ],
            [
                'module' => 'Module 3: Machine Learning Algorithms',
                'topics' => ['Supervised learning: Linear Regression, Logistic Regression', 'Decision Trees, Random Forest & Ensemble methods', 'Unsupervised learning: K-Means Clustering & PCA', 'Model evaluation metrics: Precision, Recall, ROC-AUC']
            ],
            [
                'module' => 'Module 4: Applied AI & Model Deployment',
                'topics' => ['Natural Language Processing (NLP) text analytics', 'Building predictive web apps with Streamlit / Flask', 'Model deployment on cloud servers', 'Live business analytics capstone project']
            ]
        ],
        'projects' => [
            'Customer Churn Prediction Engine for Telecom Providers',
            'Healthcare Disease Prediction System using Supervised ML',
            'Real Estate Price Prediction Model with Interactive Dashboard'
        ],
        'faqs' => [
            [
                'q' => 'Kanpur mein Data Science course ki fees aur duration kya hai?',
                'a' => 'Duration 4 se 6 mahine hai. Fees ₹5,000 se shuru hokar complete practical ML apprenticeship ke liye ₹25,000 tak hoti hai.'
            ],
            [
                'q' => 'Kya Data Science seekhne ke liye high math zaroori hai?',
                'a' => 'Basic school-level maths (linear algebra aur statistics) kaafi hai. Hamare trainers classes ke dauran maths ke practical concepts aasan bhasha mein sikhate hain.'
            ],
            [
                'q' => 'Is course mein Power BI aur SQL bhi include hain?',
                'a' => 'Haan, data cleaning, advanced SQL queries, aur Power BI dashboard development complete course ka core hissa hain.'
            ]
        ]
    ],
    'android-app-development-kanpur' => [
        'slug' => 'android-app-development-kanpur',
        'title' => 'Android App Development Course in Kanpur | LogixCode',
        'meta_description' => 'Learn Android app development in Kanpur using Kotlin, Jetpack Compose, APIs & Firebase. Build real apps for Play Store with 100% placement help. Join now!',
        'h1' => 'Android App Development Training in Kanpur (Kotlin & Jetpack Compose)',
        'badge' => 'High Mobile Demand',
        'answer_first' => 'LogixCode provides practical Android App Development training in Kanpur focusing on Kotlin, Android Studio, Jetpack Compose, REST APIs, and Firebase backends. Spanning 4 to 6 months, learners build and deploy production-ready mobile apps directly to the Google Play Store with comprehensive placement assistance.',
        'duration' => '4 to 6 Months',
        'mode' => 'Classroom (Kanpur Campus) & Live Online',
        'fees' => '₹5,000 (App Basics) to ₹25,000 (Full Android Apprenticeship)',
        'eligibility' => 'Students and software enthusiasts interested in native mobile application engineering.',
        'certificate' => 'Certified Android Mobile Application Developer',
        'trainer' => [
            'name' => 'Ashutosh Tiwari',
            'role' => 'Mobile Applications Trainer',
            'experience' => '8+ Years Industry Experience',
            'bio' => 'Mobile systems engineer with expertise in native Android development, API integrations, and cloud backends.'
        ],
        'tools' => ['Kotlin', 'Android Studio', 'Jetpack Compose', 'Firebase', 'Room DB', 'Retrofit', 'Git', 'Google Play Console'],
        'syllabus' => [
            [
                'module' => 'Module 1: Kotlin Programming Language',
                'topics' => ['Kotlin syntax, variables, null safety & functions', 'OOP in Kotlin: Classes, interfaces & data classes', 'Collections, lambdas & coroutines for background tasks']
            ],
            [
                'module' => 'Module 2: Android Fundamentals & Modern UI',
                'topics' => ['Android Studio setup & app architecture', 'Modern declarative UI with Jetpack Compose', 'Activities, Fragments, Navigation components', 'Managing device configuration & screen sizes']
            ],
            [
                'module' => 'Module 3: Local Storage, REST APIs & Firebase',
                'topics' => ['Room SQLite local database for offline support', 'Networking with Retrofit & JSON parsing', 'Firebase Authentication & Cloud Firestore', 'Push notifications & background services']
            ],
            [
                'module' => 'Module 4: Production App & Google Play Publishing',
                'topics' => ['App testing, debugging & memory profiling', 'Generating signed APK / Android App Bundle (AAB)', 'Publishing guidelines on Google Play Store', 'Resume building & mock technical interviews']
            ]
        ],
        'projects' => [
            'Food Delivery & Order Tracking Mobile App',
            'Real-Time Chat & Media Sharing Messenger',
            'Expense Tracker & Budget Planner with Cloud Sync'
        ],
        'faqs' => [
            [
                'q' => 'Kanpur mein Android training kahan se karein?',
                'a' => 'LogixCode Kanpur campus (Swarn Jayanti Vihar, Koyla Nagar) par modern Kotlin aur Jetpack Compose par based hands-on training provide karta hai.'
            ],
            [
                'q' => 'Kya hum course ke dauran live app publish karenge?',
                'a' => 'Haan! Har student ek live production-ready app build karta hai jise Google Play Store par publish karne ka process step-by-step sikhaya jata hai.'
            ],
            [
                'q' => 'Android course ke baad placement support kaisa hai?',
                'a' => 'LogixCode 100% placement support deta hai, jismein portfolio creation, GitHub repository review, aur mock technical interviews shamil hain.'
            ]
        ]
    ]
];

// Handle 404 if slug doesn't exist
if (!isset($courses[$slug])) {
    header("HTTP/1.0 404 Not Found");
    header("Location: /courses");
    exit;
}

$c = $courses[$slug];
$canonicalUrl = "https://training.logixcode.com/courses/" . $c['slug'] . "/";
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en-IN">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title><?= htmlspecialchars($c['title']) ?></title>
<meta name="description" content="<?= htmlspecialchars($c['meta_description']) ?>"/>
<link rel="canonical" href="<?= $canonicalUrl ?>"/>

<!-- Open Graph Tags -->
<meta property="og:type" content="article"/>
<meta property="og:title" content="<?= htmlspecialchars($c['title']) ?>"/>
<meta property="og:description" content="<?= htmlspecialchars($c['meta_description']) ?>"/>
<meta property="og:url" content="<?= $canonicalUrl ?>"/>
<meta property="og:site_name" content="LogixCode Training"/>
<meta property="og:image" content="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp"/>

<!-- Twitter Cards -->
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:title" content="<?= htmlspecialchars($c['title']) ?>"/>
<meta name="twitter:description" content="<?= htmlspecialchars($c['meta_description']) ?>"/>
<meta name="twitter:image" content="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp"/>

<!-- Schema Markup: Course + Breadcrumbs + EducationalOrganization -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Course",
      "@id": "<?= $canonicalUrl ?>#course",
      "name": "<?= htmlspecialchars($c['h1']) ?>",
      "description": "<?= htmlspecialchars($c['answer_first']) ?>",
      "provider": {
        "@type": "EducationalOrganization",
        "name": "LogixCode Training Institute",
        "url": "https://training.logixcode.com/",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "Shri Balaji Chauraha, 2/1 Koyla Nagar, Swarn Jayanti Vihar",
          "addressLocality": "Kanpur",
          "addressRegion": "Uttar Pradesh",
          "postalCode": "208013",
          "addressCountry": "IN"
        },
        "telephone": "+91-8467898854"
      },
      "timeRequired": "P4M",
      "hasCourseInstance": {
        "@type": "CourseInstance",
        "courseMode": ["onsite", "online"],
        "location": "Kanpur, Uttar Pradesh",
        "instructor": {
          "@type": "Person",
          "name": "<?= htmlspecialchars($c['trainer']['name']) ?>",
          "jobTitle": "<?= htmlspecialchars($c['trainer']['role']) ?>"
        }
      }
    },
    {
      "@type": "BreadcrumbList",
      "@id": "<?= $canonicalUrl ?>#breadcrumb",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "https://training.logixcode.com/"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "Courses",
          "item": "https://training.logixcode.com/courses/"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": "<?= htmlspecialchars($c['title']) ?>",
          "item": "<?= $canonicalUrl ?>"
        }
      ]
    }
  ]
}
</script>

<link rel="icon" href="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp">
<?php include_once __DIR__ . "/includes/theme.php"; ?>
</head>
<body class="bg-[var(--bg)] text-[var(--ink)] font-body">

<?php include __DIR__ . "/includes/navbar.php"; ?>

<!-- Breadcrumbs Bar -->
<div class="bg-[var(--surface)] border-b border-[var(--border)] py-3">
  <div class="lc-container">
    <nav class="flex text-xs text-[var(--muted)] font-medium items-center space-x-2" aria-label="Breadcrumb">
      <a href="<?= BASE_URL ?>" class="hover:text-[var(--teal-text)] transition-colors">Home</a>
      <span>/</span>
      <a href="<?= BASE_URL ?>courses" class="hover:text-[var(--teal-text)] transition-colors">Courses</a>
      <span>/</span>
      <span class="text-[var(--teal-text)] font-semibold truncate"><?= htmlspecialchars($c['h1']) ?></span>
    </nav>
  </div>
</div>

<!-- Course Hero Header (Component 2: --tint bg, </> motif, coral primary + outline secondary) -->
<header class="bg-[var(--tint)] border-b border-[var(--border)] py-10 md:py-16">
  <div class="lc-container">
    <div class="max-w-4xl">
      <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[var(--surface)] border border-[var(--border)] text-[var(--teal-text)] text-xs font-semibold mb-5">
        <span><?= htmlspecialchars($c['badge']) ?> · Kanpur campus</span>
        <span class="font-mono font-bold text-[var(--teal)]" aria-hidden="true">&lt;/&gt;</span>
      </div>
      <h1 class="text-[var(--ink)] font-headline mb-6">
        <?= htmlspecialchars($c['h1']) ?>
      </h1>
      
      <!-- AEO Answer-First Summary Box -->
      <div class="lc-card p-6 md:p-7 bg-[var(--surface)] mb-6 border-l-4 border-l-[var(--teal)]">
        <h2 class="text-sm font-semibold text-[var(--teal-text)] mb-2 font-headline">Course overview &amp; quick facts</h2>
        <p class="text-base text-[var(--ink)] leading-relaxed">
          <?= htmlspecialchars($c['answer_first']) ?>
        </p>
      </div>

      <!-- Quick Meta Grid -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-7">
        <div class="lc-card p-3.5 bg-[var(--surface)]">
          <span class="block text-xs text-[var(--muted)] font-medium">Duration</span>
          <span class="font-semibold text-sm text-[var(--ink)]"><?= htmlspecialchars($c['duration']) ?></span>
        </div>
        <div class="lc-card p-3.5 bg-[var(--surface)]">
          <span class="block text-xs text-[var(--muted)] font-medium">Training mode</span>
          <span class="font-semibold text-sm text-[var(--ink)]">Classroom &amp; online</span>
        </div>
        <div class="lc-card p-3.5 bg-[var(--surface)]">
          <span class="block text-xs text-[var(--muted)] font-medium">Fee structure</span>
          <span class="font-semibold text-sm text-[var(--teal-text)]">From ₹5,000</span>
        </div>
        <div class="lc-card p-3.5 bg-[var(--surface)]">
          <span class="block text-xs text-[var(--muted)] font-medium">Placement support</span>
          <span class="font-semibold text-sm text-[var(--ink)]">100% dedicated</span>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-wrap items-center gap-3">
        <a href="<?= BASE_URL ?>registration" class="btn-primary">
          Enroll now
        </a>
        <a href="<?= BASE_URL ?>contact" class="btn-secondary">
          Book free demo
        </a>
        <a href="https://wa.me/918467898854?text=Hi%20LogixCode%2C%20I%20want%20to%20know%20more%20about%20the%20<?= urlencode($c['h1']) ?>" target="_blank" class="text-sm font-semibold text-[var(--teal-text)] hover:underline px-3 min-h-[44px] inline-flex items-center gap-1.5">
          <span class="material-symbols-outlined text-lg">chat</span> Chat on WhatsApp
        </a>
      </div>
    </div>
  </div>
</header>

<!-- Highlight Strip Under Hero (Component 3: --teal bg, dark ink text) -->
<div class="bg-[var(--teal)] text-[var(--ink)] border-b border-[var(--border)]">
  <div class="lc-container py-3 flex flex-wrap items-center justify-between gap-4 text-sm font-medium">
    <span class="font-semibold text-[var(--ink)]">New batch starts Monday — morning, afternoon &amp; evening slots available</span>
    <span class="text-[var(--ink)]">Small batches (15–20 students) · ISO certified certificate</span>
  </div>
</div>

<!-- Main Course Content -->
<main class="lc-section bg-[var(--bg)]">
  <div class="lc-container">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
      
      <!-- Left 2 Cols: Syllabus & Details -->
      <div class="lg:col-span-2 space-y-12">
        
        <!-- Tools & Technologies -->
        <section>
          <h2 class="text-2xl font-bold font-headline mb-4 text-[var(--ink)] flex items-center gap-2">
            <span class="material-symbols-outlined text-[var(--teal-text)]">terminal</span> Technologies &amp; tools covered
          </h2>
          <div class="flex flex-wrap gap-2">
            <?php foreach ($c['tools'] as $tool): ?>
              <span class="lc-chip bg-[var(--surface)] text-[var(--ink)] font-medium">
                <?= htmlspecialchars($tool) ?>
              </span>
            <?php endforeach; ?>
          </div>
        </section>

        <!-- Detailed Syllabus -->
        <section>
          <h2 class="text-2xl font-bold font-headline mb-4 text-[var(--ink)] flex items-center gap-2">
            <span class="material-symbols-outlined text-[var(--teal-text)]">menu_book</span> Course syllabus
          </h2>
          <div class="space-y-4">
            <?php foreach ($c['syllabus'] as $idx => $mod): ?>
              <div class="lc-card overflow-hidden bg-[var(--surface)]">
                <div class="p-4 font-semibold font-headline text-base text-[var(--ink)] bg-[var(--tint)] border-b border-[var(--border)] flex items-center justify-between">
                  <span><?= htmlspecialchars($mod['module']) ?></span>
                  <span class="text-xs bg-[var(--teal)] text-[var(--ink)] font-semibold px-2.5 py-1 rounded-full">Part <?= $idx + 1 ?></span>
                </div>
                <div class="p-5 bg-[var(--surface)]">
                  <ul class="space-y-2">
                    <?php foreach ($mod['topics'] as $topic): ?>
                      <li class="flex items-start text-sm text-[var(--ink)]">
                        <span class="material-symbols-outlined text-[var(--teal-text)] text-base mr-2 mt-0.5">check_circle</span>
                        <span><?= htmlspecialchars($topic) ?></span>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </section>

        <!-- Real Live Projects -->
        <section>
          <h2 class="text-2xl font-bold font-headline mb-4 text-[var(--ink)] flex items-center gap-2">
            <span class="material-symbols-outlined text-[var(--teal-text)]">rocket_launch</span> Capstone live projects
          </h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <?php foreach ($c['projects'] as $proj): ?>
              <div class="lc-card p-5 bg-[var(--surface)]">
                <div class="lc-icon-circle w-9 h-9 mb-3">
                  <span class="material-symbols-outlined text-base text-[var(--ink)]">code</span>
                </div>
                <h3 class="font-semibold font-headline text-base text-[var(--ink)] mb-1"><?= htmlspecialchars($proj) ?></h3>
                <p class="text-xs text-[var(--muted)]">Built and deployed live with Git versioning, code reviews, and hosting credentials.</p>
              </div>
            <?php endforeach; ?>
          </div>
        </section>

        <!-- Visible FAQ Accordion Section (AEO optimized) -->
        <section>
          <h2 class="text-2xl font-bold font-headline mb-4 text-[var(--ink)] flex items-center gap-2">
            <span class="material-symbols-outlined text-[var(--teal-text)]">quiz</span> Frequently asked questions
          </h2>
          <div class="space-y-3">
            <?php foreach ($c['faqs'] as $f): ?>
              <div class="faq-item lc-card overflow-hidden bg-[var(--surface)]">
                <button type="button" onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-5 py-4 text-left font-semibold text-[var(--ink)] hover:bg-[var(--tint)] transition-colors min-h-[48px]">
                  <span class="text-base font-headline"><?= htmlspecialchars($f['q']) ?></span>
                  <span class="material-symbols-outlined text-[var(--teal-text)] faq-icon transition-transform duration-150">expand_more</span>
                </button>
                <div class="faq-body px-5 pb-4 pt-2 text-sm text-[var(--muted)] leading-relaxed hidden border-t border-[var(--border)]">
                  <?= htmlspecialchars($f['a']) ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </section>

      </div>

      <!-- Right Column: Sidebar (E-E-A-T Trainer Profile + Registration CTA) -->
      <aside class="space-y-6 sticky top-24">
        
        <!-- Trainer Bio Card (E-E-A-T) -->
        <div class="lc-card p-6 bg-[var(--surface)]">
          <h3 class="text-xs font-semibold text-[var(--teal-text)] font-headline mb-3">Course mentor &amp; lead</h3>
          <div class="flex items-center gap-3.5 mb-3">
            <div class="lc-icon-circle w-12 h-12 font-bold text-base font-headline">
              AT
            </div>
            <div>
              <h4 class="font-bold text-[var(--ink)] text-base font-headline"><?= htmlspecialchars($c['trainer']['name']) ?></h4>
              <p class="text-xs text-[var(--teal-text)] font-medium"><?= htmlspecialchars($c['trainer']['role']) ?></p>
              <p class="text-xs text-[var(--muted)]"><?= htmlspecialchars($c['trainer']['experience']) ?></p>
            </div>
          </div>
          <p class="text-xs text-[var(--muted)] leading-relaxed mb-3">
            <?= htmlspecialchars($c['trainer']['bio']) ?>
          </p>
          <a href="https://www.linkedin.com/company/logixcodekanpur/" target="_blank" rel="noopener" class="text-xs text-[var(--teal-text)] font-semibold hover:underline inline-flex items-center gap-1">
            Connect on LinkedIn →
          </a>
        </div>

        <!-- Campus Details Card -->
        <div class="lc-card p-6 bg-[var(--tint)]">
          <h3 class="font-bold text-base font-headline text-[var(--ink)] mb-2">Kanpur offline campus</h3>
          <p class="text-xs text-[var(--muted)] leading-relaxed mb-4">
            Shri Balaji Chauraha, 2/1 Koyla Nagar, Swarn Jayanti Vihar, Kanpur, UP 208013
          </p>
          <div class="space-y-2 text-xs text-[var(--ink)] font-medium mb-5">
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-[var(--teal-text)] text-base">call</span>
              <a href="tel:+918467898854" class="hover:underline">+91 8467898854</a>
            </div>
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-[var(--teal-text)] text-base">mail</span>
              <a href="mailto:info@logixcode.com" class="hover:underline">info@logixcode.com</a>
            </div>
          </div>
          <a href="<?= BASE_URL ?>registration" class="btn-secondary w-full text-sm">
            Register for Kanpur batch
          </a>
        </div>

      </aside>

    </div>
  </div>
</main>

<?php include __DIR__ . "/includes/footer.php"; ?>

<script>
function toggleFaq(btn) {
  const body = btn.nextElementSibling;
  const icon = btn.querySelector('.faq-icon');
  const isOpen = !body.classList.contains('hidden');
  
  document.querySelectorAll('.faq-body').forEach(b => b.classList.add('hidden'));
  document.querySelectorAll('.faq-icon').forEach(i => i.style.transform = '');
  
  if (!isOpen) {
    body.classList.remove('hidden');
    icon.style.transform = 'rotate(180deg)';
  }
}
</script>

</body>
</html>
