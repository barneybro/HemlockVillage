<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Don't touch
        self::insertData("admin", "first", "a@example.com", "2000-02-02", "123-456-7890", "admin", 1, 1); // 1
        self::insertData("supervisor", "first", "s@example.com", "1985-09-23", "321-654-9870", "supervisor", 2, 1); // 2
        self::insertData("doctor", "first", "d@example.com", "1990-03-15", "456-789-1230", "doctor", 3, 1); // 3
        self::insertData("caregiver", "first", "c@example.com", "1992-07-10", "987-654-3210", "caregiver", 4, 1); // 4
        self::insertData("patient", "first", "p@example.com", "1950-02-25", "234-567-8901", "patient", 5, 1); // 5
        self::insertData("family", "first", "f@example.com", "1995-11-17", "345-678-9012", "family", 6, 1); // 6

        // Caregivers
        self::insertData("caregiver", "two", "c2@example.com", "1992-07-10", "345-678-9013", "caregiver", 4, 1); // 7
        self::insertData("caregiver", "three", "c3@example.com", "1995-11-17", "345-678-9014", "caregiver", 4, 1); // 8
        self::insertData("caregiver", "four", "c4@example.com", "1995-11-17", "345-678-9015", "caregiver", 4, 1); // 9
        self::insertData("caregiver", "five", "c5@example.com", "1989-08-25", "345-678-9016", "caregiver", 4, 1); // 10
        self::insertData("caregiver", "six", "c6@example.com", "1990-12-11", "345-678-9017", "caregiver", 4, 1); // 11
        self::insertData("caregiver", "seven", "c7@example.com", "1991-03-14", "345-678-9018", "caregiver", 4, 1); // 12

        // All touchable below
        self::insertData("caregiver", "eight", "c8@example.com", "1993-05-22", "345-678-9019", "caregiver", 4, 1); // 13
        self::insertData("caregiver", "nine", "c9@example.com", "1994-01-18", "345-678-9020", "caregiver", 4, 1); // 14
        self::insertData("caregiver", "ten", "c10@example.com", "1996-03-14", "345-678-9021", "caregiver", 4, 1); // 15
        self::insertData("caregiver", "eleven", "c11@example.com", "1997-07-20", "345-678-9022", "caregiver", 4, 1); // 16
        self::insertData("caregiver", "twelve", "c12@example.com", "1995-12-09", "345-678-9023", "caregiver", 4, 1); // 17
        self::insertData("caregiver", "thirteen", "c13@example.com", "1993-04-14", "345-678-9024", "caregiver", 4, 1); // 18
        self::insertData("caregiver", "fourteen", "c14@example.com", "1990-11-28", "345-678-9025", "caregiver", 4, 1); // 19
        self::insertData("caregiver", "fifteen", "c15@example.com", "1988-10-05", "345-678-9026", "caregiver", 4, 1); // 20

        // Supervisors
        self::insertData("supervisor2", "lastName2", "s2@example.com", "1990-06-25", "321-555-1132", "supervisor", 2, 1); // 21
        self::insertData("supervisor3", "lastName3", "s3@example.com", "1986-08-05", "321-555-1133", "supervisor", 2, 1); // 22
        self::insertData("supervisor4", "lastName4", "s4@example.com", "1992-02-14", "321-555-1134", "supervisor", 2, 1); // 23
        self::insertData("supervisor5", "lastName5", "s5@example.com", "1989-11-19", "321-555-1135", "supervisor", 2, 1); // 24

        // Doctors
        self::insertData("Dr. John", "Doe", "d2@example.com", "1985-04-12", "555-123-4567", "doctor", 3, 1); // 25
        self::insertData("Dr. Emily", "Smith", "d3@example.com", "1979-08-21", "555-234-5678", "doctor", 3, 1); // 26
        self::insertData("Dr. Michael", "Johnson", "d4@example.com", "1982-11-03", "555-345-6789", "doctor", 3, 1); // 27
        self::insertData("Dr. Sarah", "Brown", "d5@example.com", "1990-02-17", "555-456-7890", "doctor", 3, 1); // 28
        self::insertData("Dr. David", "Davis", "d6@example.com", "1987-06-25", "555-567-8901", "doctor", 3, 1); // 29
        self::insertData("Dr. Laura", "Martinez", "d7@example.com", "1984-09-10", "555-678-9012", "doctor", 3, 1); // 30
        self::insertData("Dr. Robert", "Garcia", "d8@example.com", "1980-12-05", "555-789-0123", "doctor", 3, 1); // 31
        self::insertData("Dr. Jessica", "Wilson", "d9@example.com", "1991-01-18", "555-890-1234", "doctor", 3, 1); // 32
        self::insertData("Dr. William", "Moore", "d10@example.com", "1983-03-22", "555-901-2345", "doctor", 3, 1); // 33
        self::insertData("Dr. Olivia", "Taylor", "d11@example.com", "1986-07-14", "555-012-3456", "doctor", 3, 1); // 34

        // Patients
        self::insertData("patient", "two", "p2@example.com", "1960-03-14", "234-567-8902", "patient", 5, 1); // 35
        self::insertData("patient", "three", "p3@example.com", "1970-04-30", "234-567-8903", "patient", 5, 1); // 36
        self::insertData("patient", "four", "p4@example.com", "1980-06-12", "234-567-8904", "patient", 5, 1); // 37
        self::insertData("patient", "five", "p5@example.com", "1990-07-25", "234-567-8905", "patient", 5, 1); // 38
        self::insertData("patient", "six", "p6@example.com", "2000-08-05", "234-567-8906", "patient", 5, 1); // 39
        self::insertData("patient", "seven", "p7@example.com", "1955-09-17", "234-567-8907", "patient", 5, 1); // 40
        self::insertData("patient", "eight", "p8@example.com", "1965-10-22", "234-567-8908", "patient", 5, 1); // 41
        self::insertData("patient", "nine", "p9@example.com", "1975-11-04", "234-567-8909", "patient", 5, 1); // 42
        self::insertData("patient", "ten", "p10@example.com", "1985-12-09", "234-567-8910", "patient", 5, 1); // 43
        self::insertData("patient", "eleven", "p11@example.com", "1995-02-17", "234-567-8911", "patient", 5, 1); // 44
        self::insertData("patient", "twelve", "p12@example.com", "2005-03-30", "234-567-8912", "patient", 5, 1); // 45
        self::insertData("patient", "thirteen", "p13@example.com", "2015-04-12", "234-567-8913", "patient", 5, 1); // 46
        self::insertData("patient", "fourteen", "p14@example.com", "2020-05-05", "234-567-8914", "patient", 5, 1); // 47
        self::insertData("patient", "fifteen", "p15@example.com", "2025-06-01", "234-567-8915", "patient", 5, 1); // 48

        // Family
        self::insertData("family", "two", "f2@example.com", "1985-03-22", "345-678-9013", "family", 6, 1); // 49
        self::insertData("family", "three", "f3@example.com", "1975-05-10", "345-678-9014", "family", 6, 1); // 50
        self::insertData("family", "four", "f4@example.com", "1965-07-18", "345-678-9015", "family", 6, 1); // 51
        self::insertData("family", "five", "f5@example.com", "1990-09-14", "345-678-9016", "family", 6, 1); // 52
        self::insertData("family", "six", "f6@example.com", "1980-10-30", "345-678-9017", "family", 6, 1); // 53
        self::insertData("family", "seven", "f7@example.com", "2000-11-23", "345-678-9018", "family", 6, 1); // 54
        self::insertData("family", "eight", "f8@example.com", "1992-01-05", "345-678-9019", "family", 6, 1); // 55
        self::insertData("family", "nine", "f9@example.com", "1976-02-19", "345-678-9020", "family", 6, 1); // 56
        self::insertData("family", "ten", "f10@example.com", "1988-04-12", "345-678-9021", "family", 6, 1); // 57
        self::insertData("family", "eleven", "f11@example.com", "1993-06-09", "345-678-9022", "family", 6, 1); // 58
        self::insertData("family", "twelve", "f12@example.com", "2003-08-17", "345-678-9023", "family", 6, 1); // 59
        self::insertData("family", "thirteen", "f13@example.com", "1968-09-22", "345-678-9024", "family", 6, 1); // 60
        self::insertData("family", "fourteen", "f14@example.com", "1978-10-03", "345-678-9025", "family", 6, 1); // 61
        self::insertData("family", "fifteen", "f15@example.com", "1983-11-19", "345-678-9026", "family", 6, 1); // 62
        self::insertData("family", "sixteen", "f16@example.com", "1997-01-15", "345-678-9027", "family", 6, 1); // 63
        self::insertData("family", "seventeen", "f17@example.com", "1987-02-27", "345-678-9028", "family", 6, 1); // 64
        self::insertData("family", "eighteen", "f18@example.com", "2005-03-09", "345-678-9029", "family", 6, 1); // 65
        self::insertData("family", "nineteen", "f19@example.com", "1999-04-21", "345-678-9030", "family", 6, 1); // 66
        self::insertData("family", "twenty", "f20@example.com", "1991-06-05", "345-678-9031", "family", 6, 1); // 67

        // Other
        self::insertData("Mia", "Foster", "mia.foster@example.com", "1986-01-12", "555-928-2034", "admin123", 1, 1); // 68
        self::insertData("Ethan", "Walker", "ethan.walker@example.com", "1992-02-25", "555-837-4932", "supervisor123", 2, 1); // 69
        self::insertData("Dr. Olivia", "Hughes", "olivia.hughes@example.com", "1980-03-18", "555-746-2843", "doctor123", 3, 1); // 70
        self::insertData("Liam", "Nelson", "liam.nelson@example.com", "1994-06-04", "555-634-5732", "caregiver123", 4, 1); // 71
        self::insertData("Chloe", "Reed", "chloe.reed@example.com", "1972-08-30", "555-527-6189", "patient123", 5, 1); // 72
        self::insertData("Owen", "Parker", "owen.parker@example.com", "1996-04-17", "555-910-6723", "family123", 6, 1); // 73
        self::insertData("Harper", "Scott", "harper.scott@example.com", "1983-11-08", "555-415-9235", "admin123", 1, 1); // 74
        self::insertData("Lucas", "Evans", "lucas.evans@example.com", "1989-07-22", "555-724-8904", "supervisor123", 2, 1); // 75
        self::insertData("Dr. Isabella", "King", "isabella.king@example.com", "1985-12-03", "555-623-4517", "doctor123", 3, 1); // 76
        self::insertData("Sophia", "Morris", "sophia.morris@example.com", "1990-09-14", "555-302-8569", "caregiver123", 4, 1); // 77

        // Don't touch
        // Some unapproved users -Katy
        self::insertData("admin", "unapproved", "au@example.com", "2001-01-01", "111-222-3333", "admin", 1, 0); // 78
        self::insertData("supervisor", "unapproved", "su@example.com", "1990-04-22", "444-555-6666", "supervisor", 2, 0); // 79
        self::insertData("doctor", "unapproved", "du@example.com", "1988-06-13", "555-666-7777", "doctor", 3, 0); // 80
        self::insertData("caregiver", "unapproved", "cu@example.com", "1986-12-03", "666-777-8888", "caregiver", 4, 0); // 81
        self::insertData("patient", "unapproved. Reject me", "pu@example.com", "1965-11-30", "777-888-9999", "patient", 5, 0); // 82
        self::insertData("family", "unapproved", "fu@example.com", "1998-08-19", "888-999-0000", "family", 6, 0); // 83
        self::insertData("patient", "unapproved2. Approve me", "pu2@example.com", "1965-11-30", "777-888-9999", "patient", 5, 0); // 84

    }

    private static function insertData($firstName, $lastName, $email, $dob, $phone, $password, $roleID, $approved = 0): void
    {
        User::create([
            "first_name" => $firstName,
            "last_name" => $lastName,
            "email" => $email,
            "date_of_birth" => $dob,
            "phone_number" => $phone,
            "password" => Hash::make($password),
            "role_id" => $roleID,
            "approved" => $approved
        ]);
    }
}
