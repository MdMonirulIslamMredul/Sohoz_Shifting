<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class SampleContactSeeder extends Seeder
{
    public function run(): void
    {
        if (Contact::count() === 0) {
            Contact::create([
                'name' => 'Sarah Jenkins',
                'email' => 'sarah.jenkins@example.com',
                'phone' => '+1 (555) 234-5678',
                'subject' => 'Residential Solar Panel Installation Quote',
                'message' => "Hello Enerix team,\n\nI am interested in getting a 10kW rooftop solar system installed on my home in Springfield. Could you please provide an estimated quote and information on available tax credits?\n\nThank you,\nSarah",
                'read_at' => null, // Unread
                'is_connected' => false,
                'connected_at' => null,
                'created_at' => now()->subHours(2),
            ]);

            Contact::create([
                'name' => 'Michael Chen',
                'email' => 'mchen@enterprise-logistics.com',
                'phone' => '+1 (555) 987-6543',
                'subject' => 'Commercial Energy Storage Solutions',
                'message' => "Hi there,\n\nWe operate a warehouse facility and are looking to integrate battery storage with our existing renewable setup to manage peak demand charges. We'd like to schedule an engineering consultation.\n\nBest regards,\nMichael Chen\nOperations Director",
                'read_at' => null, // Unread
                'is_connected' => true,
                'connected_at' => now()->subHour(),
                'admin_notes' => 'Reached out via email to schedule discovery call on Thursday at 2 PM.',
                'created_at' => now()->subHours(5),
            ]);

            Contact::create([
                'name' => 'David Miller',
                'email' => 'david.miller@greenliving.org',
                'phone' => '+1 (555) 456-7890',
                'subject' => 'Partnership Inquiry for Community Clean Energy Project',
                'message' => "Greetings,\n\nOur non-profit is launching a community solar initiative and we are evaluating technical partners for equipment procurement and system maintenance. Looking forward to discussing potential collaboration.\n\nWarm regards,\nDavid",
                'read_at' => now()->subDays(1), // Read
                'is_connected' => true,
                'connected_at' => now()->subHours(18),
                'admin_notes' => 'Introductory meeting held. Sent partnership brochure and case studies.',
                'created_at' => now()->subDays(1),
            ]);

            Contact::create([
                'name' => 'Elena Rostova',
                'email' => 'elena.rostova@techsolutions.io',
                'phone' => null,
                'subject' => 'General Inquiry regarding Annual Maintenance Contracts',
                'message' => "Good morning,\n\nDo you provide preventative maintenance contracts for third-party installed solar inverters? Please let me know your inspection tiers and pricing.\n\nThanks,\nElena",
                'read_at' => now()->subDays(2), // Read
                'is_connected' => false,
                'connected_at' => null,
                'created_at' => now()->subDays(2),
            ]);
        }
    }
}
