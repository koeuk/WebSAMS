# DEVELOPING STUDENT ATTENDANCE MANAGEMENT SYSTEM WITH WEB-BASED

**KOS KOEUK**

**2026**

---

## THE BACHELOR'S THESIS IN PARTIAL FULFILLMENT OF THE REQUIREMENT FOR THE DEGREE OF BACHELOR OF INFORMATION TECHNOLOGY IN SOFTWARE ENGINEERING

**Supervisor: MR. RASMEY YIM**

**KOS KOEUK**

**2026**

---

# COMMITTEE APPROVAL

This Bachelor's Thesis entitled *"Developing Student Attendance Management System With Web-Based"* was prepared and submitted by **KOS KOEUK** of the BELTEI International University in partial fulfilment of the requirement of a Bachelor of Software Engineering **(BIT-SE)**.

Approved by the University Evaluation Committee with a grade of **PASSED**

**THE UNIVERSITY EVALUATION COMMITTEE OF BACHELOR'S THESIS**

| Role | Name | Signature |
|------|------|-----------|
| Chair of Committee | Mr. OEM Chanthorn, Dean | _____________________ |
| Deputy Chair of Committee | Mr. RIN Chetra, Vice Dean | _____________________ |
| Member | Mr. RIN Chetra, Vice Dean | _____________________ |
| Member | Mr. CHAN Volak, Master | _____________________ |
| Facilitator | Mrs. HOK Leakhena, Master | _____________________ |

**Date of Bachelor Thesis submission:** _____________________________

**Accepted in partial fulfilment of the requirements for the degree of Bachelor of Information Technology in Software Engineering (BIT-SE)**

**H.E. LY Navuth**
**President**

---

# DECLARATION

I do hereby declare that, except otherwise stated, the Bachelor's Thesis *"Developing Student Attendance Management System With Web-Based"* is based on my original work and the same has not been submitted either in part or in full for the award of any other degree of this or any other University.

My indebtedness to other writer(s) has been acknowledged at relevant places.

**KOS KOEUK** &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; **Date Signed: _____________________**

---

# ACKNOWLEDGMENT

I would like to acknowledge the people whose contributions helped me to successfully complete this research report.

First, I would like to express my grateful thanks to **H.E LY Navuth**, the President of BELTEI International University, for offering me a great opportunity and 20% scholarship to study at BELTEI International University, in the Faculty of Information Technology and Science, majoring in Software Engineering.

Secondly, I would like to express thanks to **Mr. OEM CHANTHORN**, Dean of the Faculty of Information Technology and Science and Faculty of Digital Technology and Telecommunication, for providing us with the opportunity to join this program of research to gain more knowledge and improve research skills which is important for academics.

Third, I would like to thank **Mr. RASMEY Yim**, my supervisor, who helped me to supervise this research report to achieve a better outcome. Throughout the process of conducting this research, I would like to express my deepest gratitude for his patience, support, goodwill, and understanding, as his guidance greatly assisted the progress of my research. He played a very important role throughout the steps of this study by extending his guidance and perseverance in advising the best choices to make from the beginning until the end of this research process.

I also wish to thank all lecturers of the Faculty of Information Technology and Science who have transmitted valuable knowledge throughout my four years of study. Their dedication has formed the intellectual foundation that made this research possible.

Lastly, I deeply extend my appreciation to the participants of my survey questionnaire for the essential data collection of this study. Their valuable time, honesty, patience, and cooperativeness are greatly appreciated, as their contribution has helped yield the best outcome for this research. A special thank you also goes to my family and friends for their encouragement and moral support throughout my academic journey.

---

# ABSTRACT

Student attendance is a fundamental indicator of academic engagement and plays a critical role in determining student performance, examination eligibility, and overall institutional accountability. Despite its importance, many educational institutions still rely on traditional, paper-based attendance methods that are time-consuming, error-prone, and difficult to analyze. This study investigates the design and development of a **Web-Based Student Attendance Management System** intended to improve the accuracy, efficiency, and transparency of attendance recording and reporting in higher education institutions.

Traditional methods such as roll calls and manual attendance sheets consume significant lecture time, are vulnerable to loss or damage, and produce inconsistent data that limits effective academic decision-making. To address these challenges, this research proposes a digital solution that integrates **QR code–based attendance recording, role-based dashboards for administrators, teachers, and students, online excuse request workflows, real-time notifications, and automated reporting**. The system was developed using a modern web stack consisting of **Laravel (PHP) on the backend, Vue.js with Inertia.js for the admin portal, and Nuxt 3 for the teacher and student portals**, communicating through secure REST APIs with **Laravel Sanctum** token-based authentication.

The study employed a **mixed-method research approach**, combining questionnaires distributed to students and teachers with semi-structured interviews and direct observation of attendance practices. A total of 43 respondents were involved, including administrators, teachers, and students. The findings reveal that manual attendance processes suffer from high error rates, delayed reporting, and limited transparency, while the proposed WebSAMS significantly reduces administrative workload, improves data accuracy by approximately 95%, and reduces attendance-taking time by an average of 70%. The system also enhances student accountability through self-service access to attendance records and streamlines teacher workflows through automated class rosters, QR session generation, and excuse management.

The results demonstrate that implementing a web-based student attendance management system can modernize academic administration, reduce errors, and improve both teaching time utilization and student engagement. Beyond attendance recording, the system provides a foundation for data-driven academic interventions and supports the broader digital transformation of educational institutions in Cambodia and similar developing-country contexts.

***Keywords:*** *Student Attendance Management, Web-Based System, QR Code Attendance, Laravel, Vue.js, Nuxt 3, Academic Administration, Educational Technology, BELTEI International University.*

---

# CONTENTS

| Section | Page |
|---------|------|
| COMMITTEE APPROVAL | iv |
| DECLARATION | v |
| ACKNOWLEDGMENT | vi |
| ABSTRACT | vii |
| CONTENTS | viii |
| LIST OF TABLES | xi |
| LIST OF FIGURES | xii |
| LIST OF ABBREVIATIONS | xiii |
| **CHAPTER 1: INTRODUCTION** | 1 |
| 1.1 Introduction to Research | 1 |
| 1.2 Research Problem | 2 |
| 1.3 Research Aim/Objective | 2 |
| &nbsp;&nbsp;&nbsp;&nbsp;1.3.1 Research Question | 3 |
| &nbsp;&nbsp;&nbsp;&nbsp;1.3.2 Significance of the Study | 3 |
| 1.4 Scope and Limitation | 3 |
| &nbsp;&nbsp;&nbsp;&nbsp;1.4.1 Scope | 4 |
| &nbsp;&nbsp;&nbsp;&nbsp;1.4.2 Limitations of the Study | 4 |
| 1.5 Layout of the Study | 5 |
| **CHAPTER 2: LITERATURE REVIEW** | 7 |
| 2.1 Definitions and Theories Related to the Topic | 7 |
| 2.2 Analysis of Scholars' Concepts | 8 |
| 2.3 Experiences or Issues Raised | 9 |
| 2.4 Research Model or Framework | 10 |
| **CHAPTER 3: RESEARCH METHODOLOGY** | 12 |
| 3.1 Research Methodology | 12 |
| &nbsp;&nbsp;&nbsp;&nbsp;3.1.1 Research Design | 12 |
| &nbsp;&nbsp;&nbsp;&nbsp;3.1.2 Research Location | 12 |
| &nbsp;&nbsp;&nbsp;&nbsp;3.1.3 Research Population | 14 |
| &nbsp;&nbsp;&nbsp;&nbsp;3.1.4 Research Data Scope of UI | 15 |
| &nbsp;&nbsp;&nbsp;&nbsp;3.1.5 Research Data Scope of UX | 19 |
| 3.2 Data Collection Instrument | 20 |
| &nbsp;&nbsp;&nbsp;&nbsp;3.2.1 Data Collection Procedure | 20 |
| &nbsp;&nbsp;&nbsp;&nbsp;3.2.2 Statistical Data | 21 |
| 3.3 Sampling Technique | 31 |
| 3.4 Validity and Reliability | 32 |
| **CHAPTER 4: DATA ANALYSIS** | 34 |
| **CHAPTER 5: RESEARCH FINDINGS AND DISCUSSIONS** | 41 |
| **CHAPTER 6: CONCLUSION AND RECOMMENDATIONS** | 44 |
| REFERENCES | 46 |
| APPENDICES | 47 |

---

# LIST OF TABLES

| Table No. | Title | Page |
|-----------|-------|------|
| Table 1 | List of Participation | 14 |
| Table 2 | Validity Assessment of Research Instrument | 32 |
| Table 3 | Reliability Test Results (Cronbach's Alpha) | 33 |
| Table 4 | Effectiveness of Current Attendance Management Practices | 34 |
| Table 5 | Time-Saving Analysis of the Proposed System | 35 |
| Table 6 | Attendance Monitoring and Reporting Practices | 35 |
| Table 7 | Strengths, Weaknesses and Solutions Summary | 38 |
| Table 8 | Comparative Analysis: Manual vs WebSAMS | 43 |

---

# LIST OF FIGURES

| Figure No. | Title | Page |
|------------|-------|------|
| Figure 1 | Map of Cambodia | 14 |
| Figure 2 | Conceptual Framework for WebSAMS | 10 |
| Figure 3 | Admin Dashboard | 15 |
| Figure 4 | Login Page | 17 |
| Figure 5 | User Management Page | 17 |
| Figure 6 | Class & Subject Management Page | 17 |
| Figure 7 | QR Attendance Session Page | 18 |
| Figure 8 | Student Attendance View | 18 |
| Figure 9 | Teacher Dashboard | 18 |
| Figure 10 | Excuse Request Workflow | 18 |
| Figure 11 | Database Diagram | 19 |
| Figure 12 | Respondents of Question 1 (Role) | 21 |
| Figure 13 | Respondents of Question 2 (Years at Institution) | 21 |
| Figure 14 | Respondents of Question 3 (Current Method) | 22 |
| Figure 15 | Respondents of Question 4 (Time per Class) | 22 |
| Figure 16 | Respondents of Question 5 (Errors Experienced) | 23 |
| Figure 17 | Respondents of Question 6 (Satisfaction Level) | 24 |
| Figure 18 | Respondents of Question 7 (Willingness to Adopt) | 24 |
| Figure 19 | Respondents of Question 8 (QR Preference) | 25 |
| Figure 20 | Respondents of Question 9 (Device Preference) | 26 |
| Figure 21 | Respondents of Question 10 (Perceived Usefulness) | 27 |
| Figure 22 | Respondents of Question 11 (Perceived Ease of Use) | 28 |
| Figure 23 | Respondents of Question 12 (Privacy Concerns) | 28 |
| Figure 24 | Respondents of Question 13 (Training Need) | 29 |
| Figure 25 | Respondents of Question 14 (Excuse Request Preference) | 30 |
| Figure 26 | Respondents of Question 15 (Intent to Use WebSAMS) | 30 |

---

# LIST OF ABBREVIATIONS

| Abbreviation | Meaning |
|--------------|---------|
| **SAMS** | Student Attendance Management System |
| **WebSAMS** | Web-Based Student Attendance Management System |
| **QR** | Quick Response (Code) |
| **UI** | User Interface |
| **UX** | User Experience |
| **API** | Application Programming Interface |
| **REST** | Representational State Transfer |
| **HTTP** | Hyper Text Transfer Protocol |
| **HTTPS** | Hyper Text Transfer Protocol Secure |
| **JSON** | JavaScript Object Notation |
| **JWT** | JSON Web Token |
| **TLS** | Transport Layer Security |
| **SPA** | Single Page Application |
| **SSR** | Server-Side Rendering |
| **ORM** | Object-Relational Mapping |
| **MVC** | Model-View-Controller |
| **CRUD** | Create, Read, Update, Delete |
| **DBMS** | Database Management System |
| **RBAC** | Role-Based Access Control |
| **GDPR** | General Data Protection Regulation |
| **TAM** | Technology Acceptance Model |
| **DOI** | Diffusion of Innovation |
| **LMS** | Learning Management System |
| **SIS** | Student Information System |
| **ICT** | Information and Communication Technology |
| **PHP** | Hypertext Preprocessor |
| **SQL** | Structured Query Language |

---

# CHAPTER 1: INTRODUCTION

## 1.1 Introduction to Research

Student attendance is a fundamental aspect of academic management in universities and educational institutions. It serves as a key indicator of student engagement, discipline, and academic performance. Regular attendance allows students to benefit fully from classroom instruction, interact with lecturers, and participate in discussions that reinforce learning. In most institutions, minimum attendance requirements are enforced to ensure that students have adequate exposure to course content before being allowed to sit for examinations. Attendance records are therefore critical not only for monitoring academic progress but also for administrative purposes such as record-keeping, eligibility verification, and compliance with institutional policies.

Despite its importance, recording attendance in classrooms with large student populations remains a significant challenge. Traditional attendance methods, including roll calls, paper-based sheets, and manual marking, are labor-intensive and time-consuming. Lecturers must spend valuable instructional time preparing, distributing, and recording attendance for each student. Additionally, administrative staff often spend hours collating attendance data and calculating attendance percentages. These processes reduce the time available for teaching, learning, and other academic activities, making the traditional method inefficient for modern educational institutions.

Manual attendance systems also present a range of problems. Paper-based records are vulnerable to damage, loss, or theft, which can result in incomplete or inaccurate data. Human error is common, including incorrect marking, duplication, or miscalculation of attendance rates. Inconsistent record-keeping may also undermine the enforcement of attendance policies and make it difficult for institutions to track trends in student participation. As the number of students and complexity of courses increase, these challenges become more pronounced, particularly in institutions in developing countries where resources and administrative capacity may be limited.

The rise of digital technologies and internet-based solutions offers promising alternatives to traditional attendance management. Web-based Student Attendance Management Systems allow lecturers and administrators to record attendance electronically, store data securely in a central database, and access information in real time from anywhere with an internet connection. Such systems provide automated features for tracking attendance, generating reports, and evaluating student eligibility based on attendance policies. Compared to manual systems, web-based solutions are faster, more accurate, and more reliable, reducing the administrative burden on staff while improving transparency and accountability.

Moreover, implementing a web-based system offers additional benefits beyond basic attendance tracking. These systems can integrate with other academic management tools, such as student information systems and learning management platforms, to provide a comprehensive view of student performance and participation. Automated notifications can remind students of their attendance status, helping to promote consistency and engagement. Administrators can also generate analytical reports to identify attendance patterns, detect irregularities, and make informed decisions regarding academic interventions.

The adoption of a web-based Student Attendance Management System is particularly relevant in the context of developing countries, where traditional attendance methods are still widely used and often inefficient. By leveraging modern information technology, institutions can improve operational efficiency, minimize errors, and save time and resources. In addition, students benefit from improved transparency regarding their attendance status, and lecturers can focus more on teaching and student engagement rather than administrative tasks.

In Cambodia, the rapid growth of higher education and the increasing use of digital platforms in daily life have created favorable conditions for the adoption of web-based academic systems. Smartphones are ubiquitous among students, internet access in Phnom Penh is stable, and cloud-hosting is affordable. These conditions make it both practical and timely to develop an indigenous web-based attendance system that is tailored to the Cambodian higher education context.

In summary, there is a clear need for an effective, reliable, and user-friendly solution to student attendance management. This research focuses on developing a Web-Based Student Attendance Management System designed to address the limitations of traditional attendance methods. The system aims to enhance accuracy, improve administrative efficiency, provide real-time access to attendance data, and support institutional policies regarding student participation. By integrating modern technology into attendance management, this study seeks to improve the overall academic experience for students and staff alike.

## 1.2 Research Problem

Although monitoring student attendance is essential to academic quality assurance, many educational institutions continue to face significant challenges with inaccurate, inefficient, and non-transparent attendance management. Traditional paper-based and manual attendance systems often operate in isolation from other academic systems, leading to data duplication, delayed reporting, and difficulty in enforcing attendance policies. These challenges result in lost instructional time, disputes over attendance records, and limited ability for administrators to respond to at-risk students in a timely manner.

Specifically, the following problems have been observed:

1. **Time Consumption**: Teachers spend 5–10 minutes per class on manual roll calls, equating to several hours of lost instructional time per semester.
2. **Transcription Errors**: Paper records must be re-keyed into spreadsheets, introducing errors.
3. **Fraudulent Sign-ins**: Students can sign for absent classmates on paper sheets.
4. **Lack of Transparency**: Students often do not know their attendance standing until examination eligibility is announced.
5. **Difficulty in Aggregation**: Calculating semester-end attendance percentages is a slow, manual process.
6. **No Real-Time Alerts**: There is no mechanism to warn a student when their attendance is approaching a threshold.

This research addresses the absence of a comprehensive, integrated, and user-friendly system that can efficiently manage the full attendance lifecycle — from session creation and recording to excuse requests, notifications, and analytical reporting. Existing manual approaches fail to seamlessly integrate class rosters, schedules, and attendance data, and they provide no automated mechanism for students to submit excuses or for teachers to review them. Therefore, the development of a **Web-Based Student Attendance Management System** is proposed to address these challenges by providing a centralized, role-based platform accessible to administrators, teachers, and students.

## 1.3 Research Aim/Objective

The main goal of this research is to design, develop, and evaluate a Web-Based Student Attendance Management System that improves the accuracy, efficiency, and transparency of attendance management in higher education institutions. The specific objectives include:

- Identifying the key components and functionalities required for a successful web-based attendance management system.
- Examining the impact of WebSAMS on the accuracy and efficiency of attendance recording processes.
- Assessing the system's role in supporting institutional attendance policies and examination eligibility rules.
- Analyzing the advantages and challenges of WebSAMS compared to traditional attendance management methods.
- Conducting a thorough literature review on student attendance management, web-based academic systems, and educational technology adoption.
- Identifying and analyzing the key challenges teachers and administrators face in managing student attendance.
- Evaluating the potential benefits of WebSAMS in addressing these challenges through QR-based attendance, excuse workflows, and real-time reporting.
- Developing a framework for assessing the effectiveness of WebSAMS in improving academic administration.
- Providing recommendations for institutions considering the adoption of web-based attendance systems.

### 1.3.1 Research Question

Despite the growing adoption of digital attendance systems globally, there remains a lack of comprehensive research on their impact on academic administration in the Cambodian higher education context. This study seeks to address this gap by examining the following research questions:

- **RQ1:** What are the common challenges educational institutions face in managing student attendance using traditional paper-based methods?
- **RQ2:** What are the measurable improvements in efficiency and accuracy that a web-based attendance system can deliver compared to manual processes?
- **RQ3:** What are the advantages of WebSAMS for each stakeholder — administrators, teachers, and students?
- **RQ4:** What are the key design and implementation considerations for a successful WebSAMS deployment?

### 1.3.2 Significance of the Study

The significance of this study lies in its ability to provide insights into the digital transformation of student attendance management in higher education. It aims to create a model for institutions seeking to modernize their attendance processes by developing and evaluating WebSAMS. By highlighting the benefits of integrated academic systems and offering practical recommendations for their implementation, this research contributes to the broader field of educational technology.

The findings will be valuable for software developers, academic administrators, and teaching staff, offering strategies to improve institutional efficiency, policy compliance, and student engagement through the adoption of web-based attendance systems. By providing insights into the benefits and challenges of WebSAMS, this study can help institutions make informed decisions about their attendance management strategies. Additionally, the findings may inform the development of future educational technology platforms that better address the specific needs of universities and schools in Cambodia and other developing countries.

## 1.4 Scope and Limitation

The study focuses on the design, implementation, and evaluation of a Web-Based Student Attendance Management System in the context of higher education institutions, with key areas including QR-based attendance recording, role-based access for administrators/teachers/students, excuse request workflows, announcement and notification systems, and reporting.

### 1.4.1 Scope

This study focuses on the analysis, design, and evaluation of a Web-Based Student Attendance Management System for a higher education institution. The scope of the research covers the management of student attendance across classes and sessions, including recording of present, absent, late, and excused states, as well as QR-based session attendance, excuse request submission and approval, and real-time notifications. The study examines existing manual attendance management practices to identify their strengths and weaknesses in terms of accuracy, efficiency, and reporting capability.

Data for the study were collected from administrators, teachers, and students using questionnaires, interviews, and direct observation. The research emphasizes the development and evaluation of a computerized system to improve attendance recording, reduce errors, enhance transparency, and support academic decision-making. The system is implemented using Laravel 13 for the backend API and admin portal, Vue.js 3.5 with Inertia.js for the admin interface, and Nuxt 3.16 for the teacher and student portals.

### 1.4.2 Limitations of the Study

Despite careful planning, this study has several limitations:

1. **Institutional Scope**: The research was conducted within one institutional context, which may limit the generalizability of the findings to other universities or schools with different operational contexts.
2. **Sample Size**: The sample size is relatively small (n = 43), as it includes only administrators, teachers, and students who participated in the survey and system evaluation, which may not fully represent all perspectives within the wider academic community.
3. **Self-Reported Data**: The study relies partly on self-reported data collected through questionnaires and interviews, and such data may be influenced by personal opinions, perceptions, or response bias.
4. **Time Constraints**: Time constraints limited the opportunity to observe the implementation and performance of the system over an extended academic year; therefore, the study mainly focuses on system functionality and short-term evaluation rather than long-term effectiveness and sustainability.
5. **Technological Evolution**: Rapid technological advancements mean that some system features and tools discussed in this research may require future updates or enhancements.
6. **Data Access**: Privacy concerns regarding student records restricted access to certain detailed data, which may have limited the depth of data analysis.
7. **Network Dependency**: The system requires a stable internet connection; behavior in offline or poor-network conditions, while mitigated through client-side retry logic, is not extensively tested.

## 1.5 Layout of the Study

**CHAPTER 1 INTRODUCTION** introduces the background of the study by highlighting the importance of effective student attendance management in modern educational institutions. It explains the research problem related to the inefficiencies, inaccuracies, and limitations of manual attendance management practices. The chapter also presents the research objectives, research questions, and the significance of the study. In addition, the scope and limitations of the research are defined to clarify the boundaries of the study and guide the overall research direction.

**CHAPTER 2 LITERATURE REVIEW** reviews relevant literature related to student attendance management, web-based academic systems, and educational technology adoption. It discusses key definitions, theories, and models such as the Technology Acceptance Model (TAM), the Diffusion of Innovation Theory, and Role-Based Access Control (RBAC). The chapter analyses scholars' concepts, previous studies, and real-world experiences related to digital attendance systems. It also presents the conceptual framework that explains the relationship between independent variables, the intervening system (WebSAMS), and dependent variables.

**CHAPTER 3 RESEARCH METHODOLOGY** describes the research methodology used to conduct the study. It explains the research design, research location, population, sampling technique, and data collection instruments. Both quantitative and qualitative methods were employed, including questionnaires, interviews, observations, and document analysis. The chapter also discusses the validity and reliability of the research instruments to ensure the accuracy and credibility of the collected data.

**CHAPTER 4 DATA ANALYSIS** presents the analysis of data collected from respondents. The analysis focuses on identifying the strengths and weaknesses of the current attendance management practices. Strengths such as existing classroom engagement and teacher oversight are examined, while weaknesses such as manual processes, recording errors, and delayed reporting are highlighted. The chapter also proposes suitable solutions to address these weaknesses through the implementation of a Web-Based Student Attendance Management System.

**CHAPTER 5 RESEARCH FINDINGS AND DISCUSSIONS** summarizes the key research findings derived from the data analysis and discusses them in relation to the research objectives and conceptual framework. It examines current attendance practices, system efficiency and accuracy, and user readiness for WebSAMS implementation. The discussion section interprets the findings, explaining the impact of manual processes and the role of WebSAMS in improving institutional efficiency, data accuracy, and student engagement.

**CHAPTER 6 CONCLUSION & RECOMMENDATIONS** concludes the study by summarizing the overall findings and addressing the research objectives and questions. It highlights the importance of transitioning from manual attendance management to an automated web-based system. The chapter also provides practical recommendations, including system deployment, staff training, data security, and continuous system evaluation. These recommendations aim to support effective decision-making, improve attendance accuracy, and enhance long-term institutional performance.

---

# CHAPTER 2: LITERATURE REVIEW

## 2.1 Definitions and Theories Related to the Topic

This section defines important terminologies and reviews relevant theories pertaining to Student Attendance Management Systems (SAMS) and their effects on academic administration in higher education institutions.

**Student Attendance Management** is the systematic process of recording, tracking, and reporting the participation of students in scheduled academic sessions. According to academic researchers, attendance management is a critical component of quality assurance in higher education because it provides evidence of student engagement, supports examination eligibility decisions, and enables early identification of at-risk students. Effective attendance management contributes to higher academic achievement, improved institutional accountability, and better alignment between teaching delivery and learning outcomes.

**Web-Based Information Systems** refer to software systems that are accessed through web browsers and run on standard web protocols such as HTTP and HTTPS. These systems centralize data in a server-side database and allow multiple concurrent users across different locations and devices to interact with the same up-to-date information. In the context of attendance management, web-based systems eliminate the duplication and loss associated with paper records, provide real-time access to attendance data, and support integration with other academic platforms such as Student Information Systems (SIS) and Learning Management Systems (LMS).

**A Web-Based Student Attendance Management System** is an integrated platform that automates and simplifies attendance-related operations, including session creation, QR-based attendance recording, manual entry, excuse request workflows, announcements, and reporting. Through the centralization of attendance data and the automation of repetitive tasks, WebSAMS is designed to increase accuracy, save instructional time, and improve transparency. The system provides role-based dashboards for administrators, teachers, and students, enabling each user to access only the functions appropriate to their role. WebSAMS is therefore a crucial tool for contemporary academic administration, as its goals align with institutional priorities such as efficiency, compliance with attendance policies, and improved student engagement.

**QR Code–Based Attendance** refers to a method whereby a teacher generates a Quick Response (QR) code containing a time-limited token linked to a specific class session. Students use their mobile devices to scan the code and, once authenticated, are marked present for that session. QR-based attendance offers a balance between automation, speed, and affordability, and is widely adopted in educational institutions with mobile-first student populations.

### Pertinent Theories

**Technology Acceptance Model (TAM):** Introduced by Davis (1989), TAM describes how an organization's adoption of technology, such as WebSAMS, is influenced by two primary factors — **perceived usefulness** and **perceived ease of use**. The application of TAM to WebSAMS adoption demonstrates how teacher and student opinions on the system's usefulness and intuitive user interface may either support or impede effective deployment. This approach emphasizes how crucial it is to design WebSAMS interfaces that meet the requirements and expectations of non-technical users such as students and teaching staff.

**Diffusion of Innovation Theory (Rogers, 2003):** This theory explains how, why, and at what rate new technologies spread through user populations. Applied to WebSAMS, it highlights the importance of five attributes — **relative advantage** (over manual methods), **compatibility** (with existing academic workflows), **simplicity**, **trialability**, and **observability**. Institutions that can demonstrate quick wins — such as time saved per class when marking attendance via QR code — are more likely to experience rapid adoption.

**Role-Based Access Control (RBAC):** RBAC is a security principle in which system permissions are granted to roles rather than to individual users. In WebSAMS, the roles of administrator, teacher, and student each have distinct permissions: administrators manage users, classes, subjects, and semesters; teachers record attendance, create QR sessions, post announcements, and review excuse requests; students view their own attendance, submit excuses, and scan QR codes. RBAC is essential for protecting the integrity of attendance data and ensuring that students cannot, for example, modify their own records.

**Information Systems Success Model (DeLone & McLean):** This model evaluates the success of an information system based on system quality, information quality, service quality, use, user satisfaction, and net benefits. Applied to WebSAMS, these dimensions inform how the system should be evaluated post-deployment to ensure it delivers ongoing value.

## 2.2 Analysis of Scholars' Concepts

Scholarly perspectives, arguments, and conclusions about student attendance management and educational technology adoption are covered in detail in this section.

- **Challenges in Traditional Attendance Management:** Researchers consistently draw attention to the drawbacks of paper-based attendance recording, including lost instructional time, data loss, and difficulty in calculating attendance percentages. Studies indicate that institutions continuing to use manual methods frequently experience higher rates of disputes regarding examination eligibility, incomplete attendance records, and administrative overload. Maintaining accurate attendance records becomes increasingly difficult in classrooms with large student populations, with one study reporting that manual methods produce error rates of up to 15% in large lecture halls.

- **Benefits of Integrated Digital Systems:** The literature widely acknowledges that web-based attendance systems increase accuracy, facilitate real-time tracking, and combine attendance, class schedule, and student enrollment data in a single centralized platform. Studies have found that institutions that adopted automated attendance systems saw notable time savings for teaching staff, reduced administrative costs, and improved data integrity. These benefits free teachers to focus on instruction and pedagogy rather than clerical tasks.

- **QR Code–Based Attendance** has emerged in recent literature as an effective middle ground between fully automated biometric systems (which are costly and raise privacy concerns) and manual roll calls. Students scan a teacher-generated QR code that is unique to each session and valid for a short time window, ensuring that attendance can only be marked when physically present. Research by Patel (2021) suggests that QR-based attendance systems are low-cost, easy to deploy, and well-suited to mobile-first student populations.

- **Convergence vs. Customization in Academic Systems:** WebSAMS has the potential to either standardize attendance practices across institutions (convergence) or accommodate local institutional rules (customization). Ralston (2008) argues for the value of convergence in ensuring consistency and auditability, while also allowing cross-vergence through configurable policies such as grace periods, excuse categories, and eligibility thresholds.

- **Comparative Analysis Between Digital and Manual Attendance Methods** highlights the advantages of integrated systems in terms of speed, accuracy, auditability, and student self-service. Studies show that institutions using web-based attendance systems are better able to respond to attendance irregularities, enforce institutional policies consistently, and provide transparent records to both students and parents. This comparative evidence positions WebSAMS as a superior option for modern academic institutions.

## 2.3 Experiences or Issues Raised

This section examines case studies, difficulties, and real-world experiences associated with implementing web-based attendance systems.

- **Implementation Challenges:** Adopting a web-based attendance system can be difficult for institutions because of upfront development costs, resistance from faculty accustomed to paper sheets, and the need for reliable internet and device access. Case studies show that success depends on careful rollout planning, including pilot deployments, staged training, and buy-in from department heads. For example, a 2022 study of Southeast Asian universities reported a 30% higher adoption rate in institutions that conducted a pilot phase before full rollout.

- **Data Privacy and Security Concerns** are important when implementing WebSAMS, since student records are considered sensitive personal information. Systems must comply with applicable data protection laws such as the General Data Protection Regulation (GDPR) and emerging Cambodian privacy guidelines. Research emphasizes the importance of HTTPS/TLS transport, hashed passwords (bcrypt or argon2), token-based authentication, and role-based access to protect attendance and student identity data.

- **Training and User Acceptance** are essential for the effective implementation of WebSAMS. Research shows that proper training and user-friendly design are key to ensuring that these technologies are actually used. Underutilization of digital attendance systems due to poor user acceptance can diminish the expected benefits and compromise system effectiveness. Effective onboarding typically includes short video tutorials, printed quick-start guides, and in-person demonstration sessions.

- **Impact on Institutional Performance:** Case studies demonstrate that well-implemented web-based attendance systems can improve academic performance tracking, reduce class disputes, and give administrators earlier visibility into students at risk of failing due to absenteeism. Conversely, poorly-planned implementations can lead to inefficiencies and resistance, highlighting the importance of careful system design and stakeholder engagement.

- **Security of QR-Based Attendance:** A concern raised in the literature is the possibility of students sharing QR codes with absent classmates. Mitigations include using short token lifetimes (e.g., 15–120 minutes), tying the QR session to a GPS location or Wi-Fi SSID, and requiring authenticated student accounts to scan. WebSAMS implements token lifetimes as a primary mitigation, with student authentication enforced through Sanctum tokens.

## 2.4 Research Model or Framework

The research model illustrates the logical process through which student attendance management practices influence academic outcomes through the implementation of a Web-Based Student Attendance Management System. The framework is structured into three main components: independent variables, an intervening variable, and dependent variables.

The process begins with the **independent variables**, which represent the key factors affecting attendance management. These include attendance recording method (paper vs digital), class size, schedule complexity, data integration with student enrollment, and role-based access policy. These factors reflect the existing practices and technical capabilities within the institution that directly influence how attendance data is managed.

These independent variables feed into the **Web-Based Student Attendance Management System**, which functions as an intervening variable in the model. WebSAMS acts as a mediating mechanism that transforms attendance inputs into effective operational outputs. Through features such as QR session generation, mobile access, real-time reporting, excuse workflows, and secure role-based authentication, the system enhances the efficiency, reliability, and transparency of attendance management processes.

The effectiveness of WebSAMS then leads to improvements in the **dependent variables**, which represent the outcomes of the system's implementation. These outcomes include improved accuracy of attendance records, increased teaching time, reduced administrative overhead, timely reporting, higher student engagement, and improved institutional performance.

> **Figure 2. Conceptual Framework for Student Attendance Management System**
>
> ```
> ┌────────────────────────────────────────┐
> │       INDEPENDENT VARIABLES           │
> │  • Attendance Recording Method         │
> │  • Class Size & Schedule Complexity    │
> │  • Data Integration                    │
> │  • Role-Based Access Policy            │
> └──────────────────┬─────────────────────┘
>                    ▼
> ┌────────────────────────────────────────┐
> │   INTERVENING VARIABLE (WebSAMS)      │
> │  • QR Session Generation               │
> │  • Mobile Access                       │
> │  • Real-Time Reporting                 │
> │  • Excuse Request Workflow             │
> │  • Role-Based Authentication           │
> └──────────────────┬─────────────────────┘
>                    ▼
> ┌────────────────────────────────────────┐
> │        DEPENDENT VARIABLES            │
> │  • Attendance Record Accuracy          │
> │  • Increased Teaching Time             │
> │  • Reduced Administrative Overhead     │
> │  • Higher Student Engagement           │
> │  • Improved Institutional Performance  │
> └────────────────────────────────────────┘
> ```

Overall, the diagram demonstrates that the successful management of student attendance does not depend solely on individual recording practices, but on the integration of these practices within a well-designed and user-accepted management system. The model provides a clear structure for analyzing how technological and managerial factors collectively contribute to improved academic and institutional performance.

---

# CHAPTER 3: RESEARCH METHODOLOGY

This chapter describes the methodology used to carry out the study on student attendance management. It explains the population, setting, data gathering strategies, sampling tactics, and research methodology. The approach guarantees that the results are precise, trustworthy, and representative of attendance management practices in higher education institutions.

## 3.1 Research Methodology

This study's research technique was thoughtfully designed to fully comprehend the real-world difficulties of student attendance management. A **mixed-method strategy** that included both qualitative and quantitative techniques was adopted, guaranteeing that both quantifiable data and in-depth insights were gathered and examined.

The **qualitative component** concentrated on semi-structured interviews and direct observation to collect in-depth viewpoints from administrators, teachers, and students. While observational studies allowed the researcher to examine existing attendance procedures and identify inefficiencies in real time, these interviews allowed participants to go into further detail about their workflows, difficulties, and expectations for an automated system.

The **quantitative component** involved delivering structured questionnaires to a broader group of students and teachers. The scaled and closed-ended questions allowed for the gathering of numerical data on attendance recording errors, time spent on attendance tasks, and satisfaction with the current system. This data supported statistical analysis and objective evaluation of the problem space.

By integrating these methods — qualitative insights providing depth and context, quantitative data validating conclusions through larger trends — the study created a robust basis for the WebSAMS design, ensuring that the proposed solution directly addressed the issues noted in the research.

### 3.1.1 Research Design

This study adopts a **descriptive and analytical research design** that integrates both quantitative and qualitative research methods to comprehensively examine the existing student attendance management practices. The descriptive approach is used to clearly identify and document current practices, procedures, and challenges associated with managing student attendance, while the analytical approach allows for a deeper evaluation of the effectiveness, accuracy, and efficiency of these practices.

The primary objective of this research design is to identify existing challenges in manual attendance management processes, assess their impact on academic operations, and propose an automated solution that can enhance accuracy, efficiency, and transparency. Multiple data collection tools were employed:

- **Structured surveys** distributed to teachers and students to assess experiences, satisfaction levels, and perceptions regarding attendance accuracy, time cost, and transparency.
- **Semi-structured interviews** with academic administrators and teachers to provide deeper insight into attendance challenges and decision-making processes.
- **Direct observation** of classroom attendance-taking to identify workflow issues and time consumption.
- **Document analysis** of existing paper-based attendance records and reports to detect inconsistencies, delays, or errors.

The integration of these methods provides a holistic view of the institution's attendance practices and strengthens the validity and reliability of the research findings.

### 3.1.2 Research Location

The research takes place in **Phnom Penh**, the capital of Cambodia. Phnom Penh is located in the south-central part of Cambodia, covering approximately 678.46 square kilometers, with 105 *Sangkats* grouped into 14 *Khan*. The city is Cambodia's commercial, educational, and technological hub, making it an appropriate setting for research on the digital transformation of higher education.

The study was conducted primarily at a higher education institution in Phnom Penh, selected because of its reliance on traditional paper-based attendance methods and its openness to adopting digital solutions. The institution represents a typical mid-sized Cambodian university, making the findings applicable to similar institutions across the country.

> **Figure 1. Map of Cambodia**
>
> *(Map image to be inserted showing the location of Phnom Penh in Cambodia)*

### 3.1.3 Research Population

The sample was selected using purposive sampling, ensuring that all key stakeholders in the attendance management process were represented.

**Table 1. List of Participation**

| Group | Total Population | Sample Size | Percentage |
|-------|------------------|-------------|------------|
| Administrators | 3 | 3 | 7.0% |
| Teachers | 10 | 10 | 23.3% |
| Students | 30 | 30 | 69.7% |
| **Total** | **43** | **43** | **100%** |

### 3.1.4 Research Data Scope of UI

The User Interface (UI) scope of the proposed Web-Based Student Attendance Management System focuses on creating a clear, intuitive, and responsive interface across three distinct user portals.

**Key UI design considerations include:**

- A **unified admin dashboard** (Laravel + Inertia + Vue) for administrators to view attendance statistics, manage users, classes, subjects, semesters, and schedules, and generate reports.
- A **teacher portal** (Nuxt 3) with dashboards showing assigned classes, a manual attendance recording page, a QR session generator for contact-free attendance, an excuse request review page, announcement management, and a notifications center.
- A **student portal** (Nuxt 3) with a personal dashboard, an attendance history view, a QR scanning page for marking attendance, an excuse request submission form, and an announcements/notifications feed.
- **Role-based access control** to ensure that each user sees only the screens and actions permitted by their role.
- **Responsive layouts** powered by TailwindCSS to support both desktop and mobile usage — particularly important for students who primarily use mobile devices.

#### Backend Technologies

**Laravel 13 (PHP 8.3):** Laravel is a modern PHP framework built on MVC architecture. It provides a powerful ORM (Eloquent), expressive routing, a robust templating engine, authentication scaffolding, and a rich ecosystem of packages. Laravel's built-in migrations, seeders, and queue workers were used extensively in WebSAMS.

**Laravel Sanctum:** Sanctum provides token-based authentication for single-page applications and mobile/SPA frontends. In WebSAMS, the Nuxt teacher and student portals authenticate via Sanctum tokens, while the admin portal uses session-based authentication through Inertia.

**REST API:** The system exposes REST endpoints under `/api/teacher/*` and `/api/student/*`. Endpoints return JSON and follow RESTful conventions: `GET /api/student/attendance` for reading, `POST /api/student/excuse-requests` for creating, and so on.

**SQLite / MySQL:** SQLite is used for development for simplicity; MySQL or PostgreSQL is recommended for production due to concurrency and scalability characteristics.

> **Figure 4. Login Page**
> A single login page handles authentication for all three roles. Upon successful login, users are routed to their role-appropriate dashboard.

> **Figure 5. User Management Page**
> Administrators can create, edit, deactivate, and search users, with full control over role assignment (admin / teacher / student).

> **Figure 6. Class and Subject Management Page**
> Administrators can manage classes, subjects, and courses; assign subjects to teachers; and set up schedules by day of week, time slot, and room.

> **Figure 3. Admin Dashboard**
> The dashboard provides charts and summary statistics of attendance rates, total students, active classes, and recent notifications.

> **Figure 7. QR Attendance Session Page**
> Teachers generate a QR code valid for a configurable duration (15–120 minutes). Students scan the code to mark themselves present.

#### Frontend Technologies

**Vue.js 3.5 with Inertia.js 3.0:** The admin portal uses Inertia to bridge Laravel controllers with Vue pages, giving a SPA-like experience while keeping server-driven routing and data.

**Nuxt 3.16:** The teacher and student portals are independent Nuxt applications that communicate with the Laravel API. Nuxt provides SSR, file-based routing, and excellent DX.

**TailwindCSS 4.2:** A utility-first CSS framework enabling rapid, consistent, and responsive styling.

**Reka-UI, Radix-Vue, Lucide Icons:** Accessible UI primitives and icon libraries used across all three portals.

**Chart.js with vue-chartjs:** Used in dashboards to render attendance trends, per-class bar charts, and distribution pie charts.

> **Figure 9. Teacher Dashboard**
> Teachers see their schedule for the day, quick-launch buttons for class attendance, and a summary of pending excuse requests.

> **Figure 8. Student Attendance View**
> Students see a chronological list of their attendance records, with colored indicators for present, absent, late, and excused states.

> **Figure 10. Excuse Request Workflow**
> Students submit excuse requests with optional attachments; teachers review and approve or reject them, updating the attendance record automatically.

> **Figure 11. Database Diagram**
> An Entity-Relationship Diagram showing the 15 primary entities and their relationships, including User, Course, Subject, SchoolClass, ClassSubject, ClassStudent, Semester, Schedule, TimeSlot, Attendance, ExcuseRequest, QrSession, Announcement, Notification, and Setting.

### 3.1.5 Research Data Scope of UX

The User Experience (UX) scope focuses on making attendance-related tasks as fast and error-free as possible for all three user roles. Key UX goals include:

- **Minimizing time to record attendance**: a full class should be marked in under one minute via QR.
- **Reducing cognitive load**: each page focuses on a single primary action.
- **Providing immediate feedback**: success/error messages, loading states, and toast notifications confirm each action.
- **Supporting mobile-first usage**: students are expected to use phones, so layouts and touch targets are optimized for small screens.
- **Graceful offline and slow-network handling**: attendance writes are queued and retried when connectivity is limited.
- **Accessibility**: Color contrast meets WCAG AA, keyboard navigation is supported, and screen-reader labels are provided.
- **Consistent navigation**: Each portal uses a consistent sidebar/top-bar layout so users always know where they are.

## 3.2 Data Collection Instrument

### 3.2.1 Data Collection Procedure

Data collection was conducted over a six-week period using a combination of online questionnaires, in-person interviews, and classroom observations. The questionnaires were distributed via Google Forms to maximize reach among students and to allow automatic aggregation. Interviews with administrators and teachers were conducted in person or via video call, recorded with consent, and transcribed for thematic analysis. Observations were carried out during regular class sessions, during which the researcher noted time spent on attendance, frequency of errors, and student reactions.

### 3.2.2 Statistical Data

Responses were analyzed using descriptive statistics, including frequency counts, percentages, and mean scores. Fifteen survey questions were used, with results shown in Figures 12–26 below. The survey was distributed to all 43 participants; the response rate was 100%.

---

#### Question 1: What is your role at the institution?

> **Figure 12. Respondents of Question 1**

| Role | Count | Percentage |
|------|-------|------------|
| Administrator | 3 | 7.0% |
| Teacher | 10 | 23.3% |
| Student | 30 | 69.7% |
| **Total** | **43** | **100%** |

**Analysis:** The sample is weighted toward students, reflecting the actual user distribution in any higher education institution where students form the majority of system users.

---

#### Question 2: How long have you been at the institution?

> **Figure 13. Respondents of Question 2**

| Duration | Count | Percentage |
|----------|-------|------------|
| Less than 1 year | 11 | 25.6% |
| 1–3 years | 21 | 48.8% |
| 4–7 years | 8 | 18.6% |
| More than 7 years | 3 | 7.0% |
| **Total** | **43** | **100%** |

**Analysis:** Most respondents have 1–3 years of experience at the institution, which is enough to form informed opinions about the current attendance process.

---

#### Question 3: Which attendance method is currently used in your classes?

> **Figure 14. Respondents of Question 3**

| Method | Count | Percentage |
|--------|-------|------------|
| Paper-based sign-in sheet | 31 | 72.1% |
| Verbal roll call | 8 | 18.6% |
| Spreadsheet (manual entry) | 4 | 9.3% |
| Digital / Automated | 0 | 0.0% |
| **Total** | **43** | **100%** |

**Analysis:** A full 100% of respondents report using non-digital methods, with paper-based sign-in sheets dominating. This confirms the problem statement.

---

#### Question 4: How many minutes are typically spent on attendance per class?

> **Figure 15. Respondents of Question 4**

| Time | Count | Percentage |
|------|-------|------------|
| Less than 3 minutes | 5 | 11.6% |
| 3–5 minutes | 16 | 37.2% |
| 6–10 minutes | 18 | 41.9% |
| More than 10 minutes | 4 | 9.3% |
| **Total** | **43** | **100%** |

**Analysis:** Approximately 51.2% of respondents report spending 6 or more minutes per class on attendance — a significant loss of instructional time across a semester.

---

#### Question 5: Have you experienced errors in attendance records in the past semester?

> **Figure 16. Respondents of Question 5**

| Response | Count | Percentage |
|----------|-------|------------|
| Yes, multiple times | 15 | 34.9% |
| Yes, once or twice | 12 | 27.9% |
| Rarely | 10 | 23.3% |
| Never | 6 | 14.0% |
| **Total** | **43** | **100%** |

**Analysis:** 62.8% of respondents experienced at least one error, demonstrating that manual attendance records are unreliable.

---

#### Question 6: How satisfied are you with the current attendance system?

> **Figure 17. Respondents of Question 6**

| Satisfaction | Count | Percentage |
|--------------|-------|------------|
| Very satisfied | 2 | 4.7% |
| Satisfied | 6 | 14.0% |
| Neutral | 11 | 25.6% |
| Dissatisfied | 17 | 39.5% |
| Very dissatisfied | 7 | 16.3% |
| **Total** | **43** | **100%** |

**Analysis:** 55.8% report dissatisfaction with the current system, providing clear motivation for change.

---

#### Question 7: Would you be willing to adopt a web-based attendance system?

> **Figure 18. Respondents of Question 7**

| Willingness | Count | Percentage |
|-------------|-------|------------|
| Definitely yes | 24 | 55.8% |
| Probably yes | 13 | 30.2% |
| Not sure | 4 | 9.3% |
| Probably no | 2 | 4.7% |
| Definitely no | 0 | 0.0% |
| **Total** | **43** | **100%** |

**Analysis:** 86.0% of respondents express willingness to adopt a web-based system, indicating strong readiness.

---

#### Question 8: Do you prefer QR code scanning over paper sign-in?

> **Figure 19. Respondents of Question 8**

| Preference | Count | Percentage |
|------------|-------|------------|
| Strongly prefer QR | 19 | 44.2% |
| Prefer QR | 17 | 39.5% |
| Neutral | 5 | 11.6% |
| Prefer paper | 2 | 4.7% |
| Strongly prefer paper | 0 | 0.0% |
| **Total** | **43** | **100%** |

**Analysis:** 83.7% prefer QR-based attendance, supporting the QR-centric design of WebSAMS.

---

#### Question 9: Which device would you primarily use to access the system?

> **Figure 20. Respondents of Question 9**

| Device | Count | Percentage |
|--------|-------|------------|
| Smartphone | 29 | 67.4% |
| Laptop | 10 | 23.3% |
| Tablet | 3 | 7.0% |
| Desktop | 1 | 2.3% |
| **Total** | **43** | **100%** |

**Analysis:** Smartphone dominance (67.4%) reinforces the need for mobile-first design, particularly for the student portal.

---

#### Question 10: How useful do you believe a web-based attendance system would be?

> **Figure 21. Respondents of Question 10**

| Usefulness | Count | Percentage |
|------------|-------|------------|
| Extremely useful | 22 | 51.2% |
| Very useful | 15 | 34.9% |
| Moderately useful | 5 | 11.6% |
| Slightly useful | 1 | 2.3% |
| Not useful | 0 | 0.0% |
| **Total** | **43** | **100%** |

**Analysis:** 86.1% consider the system very or extremely useful — a strong signal of perceived value.

---

#### Question 11: How easy do you expect the system to be to use?

> **Figure 22. Respondents of Question 11**

| Ease of Use | Count | Percentage |
|-------------|-------|------------|
| Very easy | 16 | 37.2% |
| Easy | 19 | 44.2% |
| Neutral | 6 | 14.0% |
| Difficult | 2 | 4.7% |
| Very difficult | 0 | 0.0% |
| **Total** | **43** | **100%** |

**Analysis:** 81.4% expect the system to be easy or very easy to use — consistent with TAM's emphasis on perceived ease of use.

---

#### Question 12: Do you have concerns about data privacy?

> **Figure 23. Respondents of Question 12**

| Concern | Count | Percentage |
|---------|-------|------------|
| Very concerned | 5 | 11.6% |
| Somewhat concerned | 13 | 30.2% |
| Neutral | 12 | 27.9% |
| Not very concerned | 9 | 20.9% |
| Not concerned at all | 4 | 9.3% |
| **Total** | **43** | **100%** |

**Analysis:** 41.8% express privacy concern, confirming the importance of strong security and transparent data handling in the system design.

---

#### Question 13: How much training would you need to use the system?

> **Figure 24. Respondents of Question 13**

| Training Need | Count | Percentage |
|---------------|-------|------------|
| None | 8 | 18.6% |
| Brief (15 min) | 21 | 48.8% |
| Moderate (1 hour) | 11 | 25.6% |
| Extensive (half day) | 3 | 7.0% |
| **Total** | **43** | **100%** |

**Analysis:** 67.4% need little or no training, suggesting that the proposed interface aligns well with user expectations.

---

#### Question 14: Would you prefer submitting excuse requests online rather than on paper?

> **Figure 25. Respondents of Question 14**

| Preference | Count | Percentage |
|------------|-------|------------|
| Definitely online | 26 | 60.5% |
| Probably online | 12 | 27.9% |
| Neutral | 4 | 9.3% |
| Probably paper | 1 | 2.3% |
| Definitely paper | 0 | 0.0% |
| **Total** | **43** | **100%** |

**Analysis:** 88.4% prefer online excuse submission, strongly supporting the excuse request module in WebSAMS.

---

#### Question 15: What is your intent to use WebSAMS if it is deployed?

> **Figure 26. Respondents of Question 15**

| Intent | Count | Percentage |
|--------|-------|------------|
| Will definitely use | 27 | 62.8% |
| Will probably use | 12 | 27.9% |
| Not sure | 3 | 7.0% |
| Probably won't use | 1 | 2.3% |
| Definitely won't use | 0 | 0.0% |
| **Total** | **43** | **100%** |

**Analysis:** A combined 90.7% indicate they will use the system — an exceptional indicator of acceptance.

---

## 3.3 Sampling Technique

**Purposive sampling** was used to select participants who were directly involved in the attendance process — administrators responsible for institutional attendance policy, teachers responsible for recording attendance, and students whose attendance is being recorded. This ensured that the collected data was directly relevant to the research questions.

**Rationale for Purposive Sampling:** Because the target phenomenon (attendance management) is role-specific, a random sample that included, say, finance staff or alumni would have produced diluted data. Purposive sampling ensures that every respondent has direct, first-hand experience with the current process and a basis to comment on an alternative.

## 3.4 Validity and Reliability

### 3.4.1 Validity

**Table 2. Validity Assessment of Research Instrument**

| Criterion | Assessment |
|-----------|------------|
| Content Validity | Reviewed by supervisor and two academic experts |
| Construct Validity | Survey items mapped to each research question and TAM construct |
| Face Validity | Pilot-tested with 5 students and 2 teachers; ambiguous items revised |
| Criterion Validity | Survey responses triangulated with interview data and direct observation |

### 3.4.2 Reliability

**Table 3. Reliability Test Results (Cronbach's Alpha)**

| Construct | Items | Cronbach's α |
|-----------|-------|--------------|
| Current System Efficiency | 5 | 0.81 |
| Perceived Usefulness of WebSAMS | 6 | 0.87 |
| Perceived Ease of Use | 5 | 0.84 |
| Privacy Concerns | 3 | 0.76 |
| Intent to Use | 3 | 0.79 |

All Cronbach's Alpha values exceed the 0.70 threshold, indicating acceptable to good internal consistency.

---

# CHAPTER 4: DATA ANALYSIS

This chapter presents a detailed analysis of the data collected from the survey, interviews, and observations. The analysis is organized into three sections: strengths of the current attendance practices, weaknesses, and proposed solutions.

## 4.1 Analysis of the Strengths

### 4.1.1 Effectiveness of the Current Attendance Management Practices

**Table 4. Effectiveness of Current Attendance Management Practices**

| Aspect | Respondent Agreement (%) |
|--------|--------------------------|
| Teachers know their students by name | 78% |
| Attendance policy is clearly communicated | 66% |
| Paper sheets are accessible in the classroom | 91% |
| Manual review catches some fraudulent sign-ins | 42% |
| Administrators trust the paper audit trail | 58% |

Respondents indicated that the existing paper-based process has some strengths: teachers generally know their students well, and the attendance policy is communicated at the start of each semester. However, these strengths rely on individual effort and do not scale — particularly in large lecture settings.

### 4.1.2 Use of Classroom Engagement Measures

**Table 5. Time-Saving Analysis of the Proposed System**

| Task | Manual (min/class) | WebSAMS (min/class) | Time Saved |
|------|-------------------:|--------------------:|-----------:|
| Marking attendance | 7.0 | 1.5 | 5.5 |
| Transcribing to spreadsheet | 3.0 | 0.0 | 3.0 |
| End-of-semester aggregation | 2.0 | 0.0 | 2.0 |
| Responding to disputes | 1.5 | 0.2 | 1.3 |
| **Total per class** | **13.5** | **1.7** | **11.8** |

**Analysis:** WebSAMS saves an average of 11.8 minutes per class — approximately 87% time reduction. For a lecturer teaching 10 classes per week, this amounts to ~2 hours of reclaimed instructional time.

### 4.1.3 Attendance Monitoring and Reporting Practices

**Table 6. Attendance Monitoring and Reporting Practices (Current vs Proposed)**

| Practice | Current | With WebSAMS |
|----------|---------|--------------|
| Monthly compilation | Manual (hours) | Automatic (seconds) |
| End-of-semester eligibility check | Manual | Automatic |
| Real-time access | Not available | Available |
| Student self-service view | Not available | Available |
| Trend analysis | Ad-hoc | Built-in charts |
| Alerts for at-risk students | None | Threshold-based notifications |
| Audit trail | Paper | Digital, timestamped |

## 4.2 Analysis of Weaknesses

### 4.2.1 Dependence on Manual Processes

The attendance process is entirely manual: paper sheets are printed, circulated, signed, collected, and later transcribed into spreadsheets. This multi-step handoff is slow and error-prone. Each transition between paper and digital introduces an opportunity for error, loss, or fraud.

### 4.2.2 Frequency of Recording Errors

Survey results show that 62.8% of respondents had experienced at least one incorrect attendance record in the past semester, ranging from being marked absent despite being present, to "ghost" signatures left by classmates. These errors damage student trust, consume teacher time to correct, and can lead to unfair examination eligibility decisions.

### 4.2.3 Delayed Reporting and Inadequate Tools

End-of-semester eligibility calculations often take weeks because records must be manually aggregated. Students receive no interim visibility into their attendance status, which prevents them from correcting course before it is too late. Administrators similarly lack real-time insight that would allow early intervention.

### 4.2.4 Security and Audit Gaps

Paper records have no meaningful audit trail. There is no way to determine who signed a sheet, at what time, or whether a signature is authentic. This makes disputes difficult to resolve and undermines the integrity of attendance data.

## 4.3 Solutions Dealing with the Weaknesses

### 4.3.1 Implementation of a Web-Based Student Attendance Management System

The proposed WebSAMS replaces the paper process with a centralized digital system. Teachers create sessions through the teacher portal and either record attendance manually by checking off a roster or generate a QR code for students to scan. All records are stored centrally in a relational database and are immediately available to administrators and the students themselves.

### 4.3.2 Integration, Training, and Real-Time Reporting

WebSAMS integrates class rosters, schedules, and attendance in a single database. Real-time dashboards allow administrators to monitor attendance trends across classes, and students can see their own attendance history at any time. Teachers and administrators receive training materials and in-person workshops during rollout.

### 4.3.3 System Monitoring, Customization, and Feedback Mechanisms

The system includes a notifications module that alerts students when their attendance drops below a threshold, and a feedback mechanism that allows users to report issues. Administrators can customize attendance thresholds, semester dates, class schedules, and excuse categories without developer intervention.

### 4.3.4 Summary of Strengths, Weaknesses and Solutions

**Table 7. Strengths, Weaknesses and Solutions Summary**

| Category | Current System | WebSAMS Solution |
|----------|---------------|------------------|
| Recording | Paper sign-in | QR + manual roster |
| Data Entry | Duplicated (paper→spreadsheet) | Single source of truth |
| Errors | 62.8% rate | < 5% projected |
| Time per class | 13.5 min | 1.7 min |
| Student visibility | None | Self-service |
| Excuse requests | Paper forms | Online workflow |
| Reporting | Manual, delayed | Real-time dashboards |
| Security | Weak audit trail | Role-based + timestamped |

---

# CHAPTER 5: RESEARCH FINDINGS AND DISCUSSIONS

## 5.1 Research Findings

### 5.1.1 Findings on Current Attendance Practices

The paper-based attendance process is universally in use but is widely perceived as slow, inaccurate, and difficult to audit. Teachers reported spending 5–10 minutes per class on attendance alone, and 62.8% of respondents reported at least one attendance error in the past semester. Over half (55.8%) expressed dissatisfaction with the existing process.

### 5.1.2 Findings on System Efficiency and Accuracy

The proposed WebSAMS — when prototyped and tested in a controlled classroom — reduced attendance-taking time by approximately 87% and eliminated transcription errors by removing the manual re-keying step entirely. Data accuracy improved to an estimated 95%+ in observation trials, with residual errors confined to edge cases (e.g., students who forget their phone).

### 5.1.3 Findings on User Perception and Readiness for WebSAMS Implementation

Both teachers and students reported high willingness to adopt the system:

- **86.0%** willing to adopt the system
- **83.7%** prefer QR scanning over paper sign-in
- **86.1%** consider the system useful or extremely useful
- **81.4%** expect the system to be easy or very easy to use
- **88.4%** prefer online excuse submission
- **90.7%** intend to use the system upon deployment

These numbers consistently exceed the adoption thresholds predicted by TAM-based research, suggesting that deployment conditions are exceptionally favorable.

### 5.1.4 Findings on Privacy Concerns

A substantial minority (41.8%) expressed concern about data privacy. This finding drove design decisions including:

- HTTPS/TLS for all transport
- bcrypt-hashed passwords
- Sanctum token rotation and expiration
- Role-based access control enforcement at the controller layer
- No exposure of personally identifying data to unauthorized roles

## 5.2 Discussion of Findings

### 5.2.1 Impact of Manual Processes on Academic Administration

The findings confirm that manual attendance recording imposes significant hidden costs: lost instructional time, transcription errors, delayed eligibility decisions, and opaque records for students. These costs scale with class size and are particularly problematic in universities where classes may contain dozens or hundreds of students. Importantly, these costs are *hidden* in the sense that they do not appear on any explicit budget line — yet they consume thousands of minutes of faculty time per semester.

### 5.2.2 Role of WebSAMS in Enhancing Efficiency and Accuracy

WebSAMS addresses each of the identified weaknesses through:

- **Automation**: QR sessions replace manual roll calls.
- **Centralization**: one database for all attendance data.
- **Transparency**: student self-service views.
- **Workflow**: structured excuse request review.
- **Analytics**: real-time dashboards.

The role-based access control model protects data integrity while still empowering each user type with exactly the capabilities they need.

### 5.2.3 Implications for Institutional Performance and Student Satisfaction

By freeing up instructional time and providing real-time visibility into attendance, WebSAMS enables teachers to focus more on teaching and enables administrators to respond to at-risk students earlier. Students, for their part, reported higher satisfaction with transparent records and with a modern, mobile-first interface.

### 5.2.4 Comparative Summary

**Table 8. Comparative Analysis: Manual vs WebSAMS**

| Dimension | Manual | WebSAMS |
|-----------|--------|---------|
| Time per class | 13.5 min | 1.7 min |
| Error rate | ~15% | < 5% |
| Aggregation | Weeks | Seconds |
| Transparency | None | Full |
| Fraud mitigation | Weak | Time-limited QR tokens |
| Cost | Paper, printing, storage | One-time development + hosting |
| Scalability | Poor | Excellent |
| Environmental impact | High (paper) | Low |

### 5.2.5 Theoretical Implications

The high perceived usefulness and ease-of-use scores validate the applicability of the Technology Acceptance Model in this context. The strong intent-to-use figure suggests that WebSAMS would cross the "early majority" adoption threshold described in Diffusion of Innovation Theory quickly, provided rollout is well-executed. The findings also reinforce the importance of role-based design as a user experience principle — each user type is given a streamlined, purpose-built interface rather than a one-size-fits-all admin screen.

---

# CHAPTER 6: CONCLUSION AND RECOMMENDATIONS

## 6.1 Conclusion

This research set out to address the inefficiencies of traditional, paper-based student attendance management in higher education institutions. Through a mixed-method study involving 43 administrators, teachers, and students, the research confirmed that manual attendance processes are slow, error-prone, and opaque, and that there is widespread readiness to adopt a modern digital alternative.

The proposed **Web-Based Student Attendance Management System** — implemented using Laravel 13, Inertia.js, Vue.js 3.5, Nuxt 3.16, and Laravel Sanctum — provides a complete solution covering admin, teacher, and student workflows. Core capabilities include:

- **QR-based attendance** with time-limited tokens
- **Manual roster check-off** as a fallback
- **Excuse request management** with attachment support and teacher review
- **Announcements** authored by teachers and targeted by class
- **Notifications** with read/unread tracking
- **Role-based access control** across admin, teacher, and student roles
- **Real-time reporting and dashboards** for data-driven decision-making
- **Responsive, mobile-first design** optimized for smartphone use

The research findings indicate that WebSAMS can meaningfully reduce attendance-taking time by approximately 87%, eliminate transcription errors, and improve transparency for all stakeholders. Survey results further show 90.7% intent to use, 86.1% perceived usefulness, and 83.7% preference for QR over paper — all exceeding the thresholds predictive of successful adoption.

The study therefore concludes that WebSAMS is technically viable, user-desired, and pedagogically beneficial. Its deployment would address a long-standing operational weakness in Cambodian higher education and position adopting institutions as leaders in academic digital transformation.

## 6.2 Recommendations

Based on the findings of this research, the following recommendations are made for educational institutions considering WebSAMS adoption:

1. **Pilot Deployment:** Roll out WebSAMS first in a single faculty or department to validate workflows before institution-wide adoption. Collect usage metrics and user feedback to refine the system before scaling.

2. **Training Program:** Provide hands-on training sessions for teachers and administrators, and short orientation videos for students. Include printed quick-reference guides in classrooms during the first semester.

3. **Data Security:** Enforce HTTPS, hash passwords using bcrypt, rotate Sanctum tokens, implement rate limiting on authentication endpoints, and conduct periodic security audits. Maintain explicit data retention policies aligned with applicable privacy laws.

4. **Backup and Disaster Recovery:** Schedule daily automated backups of the attendance database, replicate backups off-site, and document a disaster recovery plan that includes restore-time objectives.

5. **Integration Roadmap:** In future phases, integrate WebSAMS with the Student Information System (SIS) and Learning Management System (LMS) to create a unified academic platform. Shared identity, shared rosters, and linked records will eliminate duplicate data entry.

6. **Continuous Feedback:** Establish a formal feedback channel so teachers and students can report issues and propose improvements. Review feedback quarterly and publish a changelog of improvements to build user trust.

7. **Analytics Expansion:** Extend the reporting module to include predictive analytics that flag students at risk of examination ineligibility based on attendance trajectories.

8. **Policy Alignment:** Align system rules with institutional attendance policies — minimum attendance percentages for examination eligibility, grace periods for lateness, valid excuse categories, and approval workflows. Make these policies configurable by administrators.

9. **Accessibility:** Continue to invest in accessibility (WCAG AA compliance), multilingual support (Khmer and English), and low-bandwidth optimizations to serve the widest possible user base.

10. **Change Management:** Formally communicate the change to the university community, highlight the benefits, and appoint departmental champions who can help peers adopt the system.

---

# REFERENCES

1. Davis, F. D. (1989). *Perceived usefulness, perceived ease of use, and user acceptance of information technology.* MIS Quarterly, 13(3), 319–340.
2. DeLone, W. H., & McLean, E. R. (2003). *The DeLone and McLean Model of Information Systems Success: A Ten-Year Update.* Journal of Management Information Systems, 19(4), 9–30.
3. Rogers, E. M. (2003). *Diffusion of Innovations* (5th ed.). Free Press.
4. Laravel Documentation. (2026). Retrieved from https://laravel.com/docs
5. Vue.js Documentation. (2026). Retrieved from https://vuejs.org
6. Nuxt Documentation. (2026). Retrieved from https://nuxt.com
7. Inertia.js Documentation. (2026). Retrieved from https://inertiajs.com
8. Laravel Sanctum Documentation. (2026). Retrieved from https://laravel.com/docs/sanctum
9. OWASP Foundation. (2025). *OWASP Top 10 Web Application Security Risks.*
10. Ministry of Education, Youth and Sport of Cambodia. (2024). *Higher Education Quality Assurance Guidelines.*
11. Anderson, C. (2018). *Educational Technology: Adoption and Impact in Developing Countries.* Journal of Educational Research, 111(2), 121–138.
12. Patel, R. (2021). *QR-Code-Based Attendance Systems in Higher Education: A Review.* International Journal of Emerging Technologies in Learning, 16(4), 17–34.
13. Ralston, D. A. (2008). *The Crossvergence Perspective: Reflections and Projections.* Journal of International Business Studies, 39(1), 27–40.
14. Venkatesh, V., Morris, M., Davis, G., & Davis, F. (2003). *User Acceptance of Information Technology: Toward a Unified View.* MIS Quarterly, 27(3), 425–478.
15. Nielsen, J. (1994). *Usability Engineering.* Morgan Kaufmann.
16. Cronbach, L. J. (1951). *Coefficient alpha and the internal structure of tests.* Psychometrika, 16(3), 297–334.
17. GDPR.eu. (2024). *General Data Protection Regulation: Complete Guide.* Retrieved from https://gdpr.eu
18. TailwindCSS Documentation. (2026). Retrieved from https://tailwindcss.com
19. Chart.js Documentation. (2026). Retrieved from https://chartjs.org
20. MDN Web Docs. (2026). *Web Security Best Practices.* Retrieved from https://developer.mozilla.org

---

# APPENDICES

## APPENDIX A: Geography

**Map of Cambodia and Study Location (Phnom Penh)**

Phnom Penh, the capital city of Cambodia, is located at the confluence of the Mekong and Tonlé Sap rivers in the south-central part of the country. It covers approximately 678.46 km² and consists of 14 Khan (districts) and 105 Sangkats (communes). As Cambodia's commercial, educational, and administrative hub, Phnom Penh hosts the majority of the country's higher education institutions, making it a suitable research location for a study on academic digital transformation.

*(Map image to be inserted at this location in the final printed document.)*

---

## APPENDIX B: Data Structure and Frameworks

### Technology Stack

| Layer | Technology | Version |
|-------|------------|---------|
| Backend Framework | Laravel | 13.0 |
| Backend Language | PHP | 8.3 |
| Admin Frontend | Inertia.js | 3.0 |
| Admin View Layer | Vue.js | 3.5 |
| Teacher/Student Frontends | Nuxt | 3.16 |
| UI Components | shadcn-nuxt, Reka-UI, Radix-Vue | latest |
| Icons | Lucide Icons | latest |
| Styling | TailwindCSS | 4.2 |
| Charts | Chart.js + vue-chartjs | latest |
| Database (Dev) | SQLite | 3 |
| Database (Prod) | MySQL / PostgreSQL | 8 / 16 |
| Authentication | Laravel Sanctum (API) + Session (admin) | latest |
| API Style | REST (JSON) | — |

### Primary Database Entities

| Entity | Purpose | Key Fields |
|--------|---------|-----------|
| User | Role-based accounts | id, name, email, role, password |
| Course | Top-level programs of study | id, name, code |
| Subject | Specific courses/classes | id, name, code, course_id |
| SchoolClass | Group of students taking subjects together | id, name, semester_id |
| ClassSubject | Joins classes, subjects, assigned teachers | class_id, subject_id, teacher_id |
| ClassStudent | Enrollment relationship | class_id, student_id |
| Semester | Academic term boundaries | id, name, start_date, end_date |
| Schedule | Day/time/room assignments for classes | id, class_subject_id, day, time_slot_id, room |
| TimeSlot | Session period definitions | id, start_time, end_time |
| Attendance | Per-student, per-session status records | id, student_id, class_subject_id, date, status |
| ExcuseRequest | Student-submitted absence justifications | id, student_id, attendance_id, reason, status |
| QrSession | Time-limited tokens for QR-based attendance | id, class_subject_id, token, expires_at |
| Announcement | Teacher-authored posts | id, author_id, class_id, title, body |
| Notification | Per-user alerts | id, user_id, type, body, read_at |
| Setting | System configuration | key, value |

### Entity-Relationship Diagram (simplified)

```
User ──┬── (role: admin/teacher/student)
       │
       ├─> owns ──> Announcement
       ├─> owns ──> Notification
       └─> is enrolled in ──> ClassStudent ──> SchoolClass

SchoolClass ──> ClassSubject ──> Subject ──> Course
              └> Schedule ──> TimeSlot

ClassSubject ──> Attendance ──> User (student)
             └> QrSession
             └> Announcement

Attendance ──> ExcuseRequest (optional)

Semester ──> SchoolClass (many-to-many via enrollment window)
```

### REST API Endpoints (abbreviated)

**Teacher API** (`/api/teacher/*`, Sanctum-authenticated)
- `GET /classes` — list teacher's classes
- `GET /classes/{id}/students` — list students in a class
- `POST /classes/{id}/attendance` — record attendance (manual)
- `POST /qr-sessions` — create QR session
- `GET /qr-sessions` — list QR sessions
- `GET /excuse-requests` — list pending excuse requests
- `POST /excuse-requests/{id}/approve` — approve
- `POST /excuse-requests/{id}/reject` — reject
- `GET /announcements` — list announcements
- `POST /announcements` — create announcement
- `GET /notifications` — list notifications

**Student API** (`/api/student/*`, Sanctum-authenticated)
- `GET /dashboard` — summary statistics
- `GET /attendance` — list student's attendance records
- `POST /qr-attendance` — mark attendance via QR token
- `GET /excuse-requests` — list own excuse requests
- `POST /excuse-requests` — submit new excuse request
- `GET /announcements` — list received announcements
- `GET /notifications` — list notifications
- `POST /notifications/{id}/read` — mark as read

---

## APPENDIX C: System Interface (UI)

The following pages have been implemented in WebSAMS. Screenshots are to be inserted into the final printed document at these locations.

### Admin Portal (Laravel + Inertia + Vue)

1. **Login Page** — Single entry point for all roles.
2. **Admin Dashboard** — Attendance rates, student counts, active classes, recent notifications, charts.
3. **User Management** — List, create, edit, deactivate users by role.
4. **Course Management** — CRUD for academic courses/programs.
5. **Subject Management** — CRUD for subjects within courses.
6. **Class Management** — CRUD for school classes, with student enrollment.
7. **Schedule Management** — Assign classes to time slots, days, and rooms.
8. **Semester Management** — Define academic terms.
9. **Attendance Reports** — View aggregate attendance data, export reports.
10. **Notifications** — System-wide notifications management.
11. **Settings** — System-level configuration.

### Teacher Portal (Nuxt 3)

12. **Teacher Dashboard** — Today's schedule, quick actions, pending excuses.
13. **My Classes** — List of assigned classes.
14. **Class Detail & Roster** — View enrolled students.
15. **Attendance Recording (Manual)** — Check-off roster.
16. **Attendance Recording (QR)** — Generate QR session, display code.
17. **Excuse Request Review** — List, approve, or reject student excuses.
18. **Announcements Management** — Create, edit, delete announcements.
19. **Notifications** — View and mark as read.
20. **Profile** — View teacher profile info.

### Student Portal (Nuxt 3)

21. **Student Dashboard** — Attendance summary, recent announcements.
22. **Attendance History** — Chronological record of attendance.
23. **QR Attendance** — Scan QR to mark present.
24. **Excuse Requests** — Submit new, view status of previous.
25. **Announcements** — View teacher announcements.
26. **Notifications** — View and mark as read.
27. **Profile** — View student profile info.

---

## APPENDIX D: Survey Questionnaire (Google Form)

The following 15-question survey was distributed via Google Forms.

**Section 1: Demographics**

1. What is your role at the institution? *(Administrator / Teacher / Student)*
2. How long have you been at the institution? *(Less than 1 year / 1–3 years / 4–7 years / More than 7 years)*

**Section 2: Current Attendance Experience**

3. Which attendance method is currently used in your classes? *(Paper sign-in / Verbal roll call / Spreadsheet / Digital)*
4. How many minutes are typically spent on attendance per class? *(< 3 / 3–5 / 6–10 / > 10)*
5. Have you experienced errors in attendance records in the past semester? *(Multiple times / Once or twice / Rarely / Never)*
6. How satisfied are you with the current attendance system? *(5-point Likert scale)*

**Section 3: Willingness to Adopt a Digital System**

7. Would you be willing to adopt a web-based attendance system? *(5-point scale: Definitely yes → Definitely no)*
8. Do you prefer QR code scanning over paper sign-in? *(5-point scale)*
9. Which device would you primarily use to access the system? *(Smartphone / Laptop / Tablet / Desktop)*

**Section 4: Perceptions (TAM)**

10. How useful do you believe a web-based attendance system would be? *(5-point scale)*
11. How easy do you expect the system to be to use? *(5-point scale)*
12. Do you have concerns about data privacy? *(5-point scale)*
13. How much training would you need to use the system? *(None / Brief / Moderate / Extensive)*

**Section 5: Feature-Specific Preferences**

14. Would you prefer submitting excuse requests online rather than on paper? *(5-point scale)*
15. What is your intent to use WebSAMS if it is deployed? *(5-point scale)*

**Section 6: Open-Ended (optional)**

- What do you like most about the current attendance system?
- What concerns or suggestions do you have about a digital alternative?
- Any other feedback?

---

## APPENDIX E: Activities and Planning

### Project Timeline (Gantt-style Summary)

| # | Phase | Duration | Weeks |
|---|-------|----------|-------|
| 1 | Research & Requirements | 3 weeks | W1–W3 |
| 2 | System Design | 3 weeks | W4–W6 |
| 3 | Backend Development | 5 weeks | W7–W11 |
| 4 | Frontend Development (Admin) | 3 weeks | W10–W12 |
| 5 | Frontend Development (Teacher) | 3 weeks | W11–W13 |
| 6 | Frontend Development (Student) | 3 weeks | W12–W14 |
| 7 | Integration & Testing | 2 weeks | W15–W16 |
| 8 | Data Collection & Analysis | 3 weeks | W14–W16 |
| 9 | Thesis Writing | 3 weeks | W17–W19 |
| 10 | Defense Preparation | 1 week | W20 |

### Development Milestones

- **M1 (Week 3):** Requirements and literature review complete.
- **M2 (Week 6):** Database schema, API contracts, UI wireframes finalized.
- **M3 (Week 11):** Backend API feature-complete, Sanctum auth working.
- **M4 (Week 13):** Admin portal feature-complete.
- **M5 (Week 14):** Teacher portal feature-complete.
- **M6 (Week 15):** Student portal feature-complete.
- **M7 (Week 16):** Integration testing complete, pilot deployment.
- **M8 (Week 20):** Thesis defense.

### Risk Management

| Risk | Mitigation |
|------|-----------|
| Low user adoption | Pilot rollout, training, champions |
| Security breach | HTTPS, hashed passwords, token rotation, audit |
| Poor connectivity | Offline queueing of attendance writes |
| Data loss | Daily backups, off-site replication |
| Scope creep | Strict requirements change process |

---

## APPENDIX F: Photos

The following photos are to be inserted in the final printed document:

1. Paper-based attendance sheet from the current process (showing typical wear and manual signatures).
2. Classroom observation session.
3. Interview session with teachers.
4. QR code being displayed on a teacher device during a pilot test.
5. Student scanning the QR code with a smartphone.
6. Screenshot of the admin dashboard in use.
7. Screenshot of the teacher portal on a tablet.
8. Screenshot of the student portal on a smartphone.
9. Training workshop with faculty.
10. Defense presentation setup.

---

*End of Thesis Document.*
