<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $faker = FakerFactory::create('en_US'); // Ensures English content

        $status = $faker->randomElement(['pending', 'in progress', 'completed']);

        return [
            // 'user_id' will be set manually in the seeder
            'title' => $faker->randomElement([
                'Fix login issue',
                'Design user profile page',
                'Implement email notifications',
                'Update database schema',
                'Review pull request',
                'Write unit tests',
                'Deploy to staging server',
                'Prepare weekly report',
                'Optimize API performance',
                'Refactor task controller',
                'Schedule client meeting',
                'Update documentation',
                'Plan sprint retrospective',
                'Integrate Stripe payment gateway',
                'Create onboarding checklist',
                'Clean up old branches',
                'Set up CI/CD pipeline',
                'Configure CORS policy',
                'Add forgot password feature',
                'Test mobile responsiveness',
                'Migrate legacy codebase',
                'Analyze server logs',
                'Write API usage guide',
                'Redesign homepage layout',
                'Enable two-factor authentication',
                'Implement search functionality',
                'Fix typo on About page',
                'Assign tasks to team',
                'Track daily standup notes',
                'Validate user input'
            ]),
            'description' => $faker->randomElement([
                'Ensure mobile compatibility on all screen sizes.',
                'Verify that form validation handles all edge cases.',
                'Complete this task before the next release.',
                'Collaborate with frontend team for UI integration.',
                'Write documentation for the new endpoints.',
                'Fixes bug from issue #134 reported by QA.',
                'Add loading indicators for slow API calls.',
                'Improve readability of code in TaskController.',
                'Use mock data for development testing.',
                'Send confirmation email after registration.',
                'This task is urgent and affects user onboarding.',
                'Replace hardcoded values with config variables.',
                'Improve query performance on dashboard stats.',
                'Double-check environment variables in production.',
                'Reach out to DevOps for server access.',
                'All fields must be localized for international users.',
                'Include unit and feature tests.',
                'Tag related tasks for visibility.',
                'Reproduce bug on Safari browser.',
                'Document the changes for the changelog.',
                'Update package dependencies to latest versions.',
                'Create fallback for missing user avatars.',
                'Push branch before noon for code review.',
                'Limit file upload size to 5MB.',
                'Confirm layout consistency across modules.',
                'Add pagination to large datasets.',
                'Replace alert with toast notification.',
                'Consult UX team before changing nav menu.',
                'Test with both admin and guest accounts.',
                'Make sure tests pass before merging.'
            ]),
            'status' => $status,
            'completed_at' => $status === 'completed' ? now()->subDays(rand(0, 10)) : null,
        ];
    }
}
