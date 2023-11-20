<?php

namespace App\Library\Enums;

enum Occupation: string implements ShouldReturnValues
{
    use ReturnsValues;

    public const data = [
        "Accountant"     => "Accountant",
        "Actor" => "Actor",
        "Actuary"     => "Actuary",
        "Administrator" => "Administrator",
        "Agent"     => "Agent",
        "Agricultural_Worker" => "Agricultural Worker",
        "Air_Traffic_Controller"     => "Air Traffic Controller",
        "Ambulance_Officer" => "Ambulance Officer",
        "Architect"     => "Architect",
        "Art_Director" => "Art Director",
        "Artist"     => "Artist",
        "Athlete" => "Athlete",
        "Auditor"     => "Auditor",
        "Baker" => "Baker",
        "Barista"     => "Barista	",
        "Bartender" => "Bartender",
        "Board_Member"     => "Board Member",
        "Business_Analyst" => "Business Analyst",
        "Business_Owner" => "Business Owner",
        "Chef" => "Chef",
        "Childcare_Worker" => "Childcare Worker",
        "Civil_Servant"     => "Civil Servant	",
        "Cleaner" => "Cleaner",
        "Company_Worker"     => "Company Worker	",
        "Computer_Scientist" => "Computer Scientist",
        "Construction_Worker"     => "Construction Worker	",
        "Consultant" => "Consultant",
        "Cook"     => "Cook",
        "Copywriter" => "Copywriter",
        "Courier"     => "Courier	",
        "Craftsperson" => "Craftsperson",
        "Curator"     => "Curator	",
        "Customer_Service_Representative" => "Customer Service Representative",
        "Dentist"     => "Dentist	",
        "Designer" => "Designer",
        "Doctor"     => "Doctor	",
        "Driver" => "Driver",
        "Economist"     => "Economist",
        "Electrician" => "Electrician",
        "Entrepreneur"     => "Entrepreneur",
        "Equipment_Operator" => "Equipment Operator",
        "Executive_Manager"     => "Executive Manager",
        "Farmer" => "Farmer",
        "Film_Maker"     => "Film Maker",
        "Firefighter" => "Firefighter",
        "Fisherman"     => "Fisherman",
        "Fitness_Trainer" => "Fitness Trainer",
        "Flight_Attendant"     => "Flight Attendant",
        "Florist" => "Florist",
        "Freelancer"     => "Freelancer",
        "Gardener" => "Gardener",
        "Hair_Stylist"     => "Hair Stylist",
        "Home_Care_Aid" => "Home Care Aid",
        "Homemaker"     => "Homemaker",
        "Hospitality_Worker" => "Hospitality Worker",
        "Housekeeper"     => "Housekeeper	",
        "Information_Technology_Professional" => "Information Technology Professional",
        "Investor"     => "Investor",
        "Journalist" => "Journalist",
        "Laborer"     => "Laborer",
        "Lawyer" => "Lawyer",
        "Librarian"     => "Librarian	",
        "Maintenance_Worker" => "Maintenance Worker",
        "Manager"     => "Manager",
        "Manufacturing_Worker" => "Manufacturing Worker",
        "Marketing_Professional"     => "Marketing Professional",
        "Mathematician" => "Mathematician",
        "Mechanic"     => "Mechanic",
        "Medical_Professional" => "Medical Professional",
        "Merchant"     => "Merchant",
        "Midwife" => "Midwife",
        "Military_Personnel"     => "Military Personnel",
        "Musician" => "Musician",
        "Nanny"     => "Nanny	",
        "Nurse" => "Nurse",
        "Office_Clerk"     => "Office Clerk",
        "Office_Worker" => "Office Worker",
        "Optician"     => "Optician",
        "Performance_Artist" => "Performance Artist",
        "Photographer"     => "Photographer",
        "Pilot" => "Pilot",
        "Police_Officer"     => "Police Officer",
        "Politician" => "Politician",
        "Professor" => "Professor",
        "Railroad_Engineer" => "Railroad Engineer",
        "Researcher" => "Researcher",
        "Retail_Employee" => "Retail Employee",
        "Retiree" => "Retiree",
        "Rigger" => "Rigger",
        "Sailor" => "Sailor",
        "Salesperson" => "Salesperson",
        "Scientist" => "Scientist",
        "Sea_Captain" => "Sea Captain",
        "Secretary" => "Secretary",
        "Self_Employed" => "Self-Employed",
        "Small_Business_Owner" => "Small Business Owner",
        "Social_Worker" => "Social Worker",
        "Software_Developer"     => "Software Developer",
        "Spokesperson" => "Spokesperson",
        "Statistician" => "Statistician",
        "Stay_At_Home_Parent" => "Stay-At-Home Parent",
        "Student" => "Student",
        "Surgeon" => "Surgeon",
        "Surveyor" => "Surveyor",
        "Tailor" => "Tailor",
        "Teacher" => "Teacher",
        "Technician" => "Technician",
        "Tradesperson" => "Tradesperson",
        "Trainer" => "Trainer",
        "Translator" => "Translator",
        "Truck_Driver" => "Truck Driver",
        "Tutor" => "Tutor",
        "Unemployed" => "Unemployed",
        "Veterinarian" => "Veterinarian",
        "Videographer" => "Videographer",
        "Waiter" => "Waiter",
        "Waste_Collector" => "Waste Collector",
        "Writer" => "Writer",
        "Zoologist" => "Zoologist",
    ];

    case Accountant     = "Accountant";
    case Actor = "Actor";
    case Actuary     = "Actuary";
    case Administrator = "Administrator";
    case Agent     = "Agent";
    case Agricultural_Worker = "Agricultural Worker";
    case Air_Traffic_Controller     = "Air Traffic Controller";
    case Ambulance_Officer = "Ambulance Officer";
    case Architect     = "Architect	";
    case Art_Director = "Art Director";
    case Artist     = "Artist";
    case Athlete = "Athlete";
    case Auditor     = "Auditor";
    case Baker = "Baker";
    case Barista     = "Barista	";
    case Bartender = "Bartender";
    case Board_Member     = "Board Member";
    case Business_Analyst = "Business Analyst";
    case Business_Owner = "Business Owner";
    case Chef = "Chef";
    case Childcare_Worker = "Childcare Worker";
    case Civil_Servant     = "Civil Servant	";
    case Cleaner = "Cleaner";
    case Company_Worker     = "Company Worker	";
    case Computer_Scientist = "Computer Scientist";
    case Construction_Worker     = "Construction Worker	";
    case Consultant = "Consultant";
    case Cook     = "Cook	";
    case Copywriter = "Copywriter";
    case Courier     = "Courier	";
    case Craftsperson = "Craftsperson";
    case Curator     = "Curator	";
    case Customer_Service_Representative = "Customer Service Representative";
    case Dentist     = "Dentist	";
    case Designer = "Designer";
    case Doctor     = "Doctor	";
    case Driver = "Driver";
    case Economist     = "Economist";
    case Electrician = "Electrician";
    case Entrepreneur     = "Entrepreneur";
    case Equipment_Operator = "Equipment Operator";
    case Executive_Manager     = "Executive Manager";
    case Farmer = "Farmer";
    case Film_Maker     = "Film Maker";
    case Firefighter = "Firefighter";
    case Fisherman     = "Fisherman";
    case Fitness_Trainer = "Fitness Trainer";
    case Flight_Attendant     = "Flight Attendant";
    case Florist = "Florist";
    case Freelancer     = "Freelancer";
    case Gardener = "Gardener";
    case Hair_Stylist     = "Hair Stylist";
    case Home_Care_Aid = "Home Care Aid";
    case Homemaker     = "Homemaker";
    case Hospitality_Worker = "Hospitality Worker";
    case Housekeeper     = "Housekeeper	";
    case Information_Technology_Professional = "Information Technology Professional";
    case Investor     = "Investor";
    case Journalist = "Journalist";
    case Laborer     = "Laborer";
    case Lawyer = "Lawyer";
    case Librarian     = "Librarian	";
    case Maintenance_Worker = "Maintenance Worker";
    case Manager     = "Manager";
    case Manufacturing_Worker = "Manufacturing Worker";
    case Marketing_Professional     = "Marketing Professional";
    case Mathematician = "Mathematician";
    case Mechanic     = "Mechanic";
    case Medical_Professional = "Medical Professional";
    case Merchant     = "Merchant";
    case Midwife = "Midwife";
    case Military_Personnel     = "Military Personnel";
    case Musician = "Musician";
    case Nanny     = "Nanny	";
    case Nurse = "Nurse";
    case Office_Clerk     = "Office Clerk";
    case Office_Worker = "Office Worker";
    case Optician     = "Optician";
    case Performance_Artist = "Performance Artist";
    case Photographer     = "Photographer";
    case Pilot = "Pilot";
    case Police_Officer     = "Police Officer";
    case Politician = "Politician";
    case Professor = "Professor";
    case Railroad_Engineer = "Railroad Engineer";
    case Researcher = "Researcher";
    case Retail_Employee = "Retail Employee";
    case Retiree = "Retiree";
    case Rigger = "Rigger";
    case Sailor = "Sailor";
    case Salesperson = "Salesperson";
    case Scientist = "Scientist";
    case Sea_Captain = "Sea Captain";
    case Secretary = "Secretary";
    case Self_Employed = "Self-Employed";
    case Small_Business_Owner = "Small Business Owner";
    case Social_Worker = "Social Worker";
    case Software_Developer     = "Software Developer";
    case Spokesperson = "Spokesperson";
    case Statistician = "Statistician";
    case Stay_At_Home_Parent = "Stay-At-Home Parent";
    case Student = "Student";
    case Surgeon = "Surgeon";
    case Surveyor = "Surveyor";
    case Tailor = "Tailor";
    case Teacher = "Teacher";
    case Technician = "Technician";
    case Tradesperson = "Tradesperson";
    case Trainer = "Trainer";
    case Translator = "Translator";
    case Truck_Driver = "Truck Driver";
    case Tutor = "Tutor";
    case Unemployed = "Unemployed";
    case Veterinarian = "Veterinarian";
    case Videographer = "Videographer";
    case Waiter = "Waiter";
    case Waste_Collector = "Waste Collector";
    case Writer = "Writer";
    case Zoologist = "Zoologist";
}
