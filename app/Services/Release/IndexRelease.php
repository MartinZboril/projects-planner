<?php

namespace App\Services\Release;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class IndexRelease
{
    public $releases;

    public $actualRelease;

    public function __construct()
    {
        $releaseHistory = $this->releaseHistory();

        $this->releases = $releaseHistory;
        $this->actualRelease = $releaseHistory->keys()->last();
    }

    /**
     * Get releases for index.
     */
    public function getReleases(): Collection
    {
        $items = collect([
            'releases' => $this->releases,
            'actual_release' => $this->actualRelease,
        ]);

        return $items;
    }

    /**
     * Get collection with release history
     */
    protected function releaseHistory(): Collection
    {
        return collect([
            '0.0.1' => [
                'subject' => 'Set Up project',
                'realese_date' => Carbon::create(2022, 2, 13, 0),
                'description' => 'Set up development tools for project and Implement adminLTE theme.',
                'background' => '',
            ],
            '0.0.3' => [
                'subject' => 'Added users',
                'realese_date' => Carbon::create(2022, 2, 18, 0),
                'description' => 'Add CRUD actions for users and login/logout.',
                'background' => '',
            ],
            '0.0.5' => [
                'subject' => 'Added clients',
                'realese_date' => Carbon::create(2022, 2, 20, 0),
                'description' => 'Add CRUD actions for clients.',
                'background' => '',
            ],
            '0.0.8' => [
                'subject' => 'Added projects',
                'realese_date' => Carbon::create(2022, 2, 22, 0),
                'description' => 'Add CRUD actions and team for projects.',
                'background' => '',
            ],
            '0.1.0' => [
                'subject' => 'Added tasks to projects',
                'realese_date' => Carbon::create(2022, 3, 15, 0),
                'description' => 'Add CRUD actions for projects.',
                'background' => 'indigo',
            ],
            '0.1.2' => [
                'subject' => 'Statuses for tasks',
                'realese_date' => Carbon::create(2022, 3, 30, 0),
                'description' => 'Link statuses with tasks.',
                'background' => '',
            ],
            '0.1.5' => [
                'subject' => 'Extensions for tasks',
                'realese_date' => Carbon::create(2022, 4, 27, 0),
                'description' => 'Implement kanban with tasks in projects and added ToDos for tasks.',
                'background' => '',
            ],
            '0.1.6' => [
                'subject' => 'Time measurement for projects',
                'realese_date' => Carbon::create(2022, 5, 18, 0),
                'description' => '',
                'background' => '',
            ],
            '0.1.7' => [
                'subject' => 'Added rates to time measurement',
                'realese_date' => Carbon::create(2022, 6, 5, 0),
                'description' => '',
                'background' => '',
            ],
            '0.1.8' => [
                'subject' => 'Added tickets to projects',
                'realese_date' => Carbon::create(2022, 7, 9, 0),
                'description' => 'Add CRUD actions for tickets and convertions ticket to task.',
                'background' => '',
            ],
            '0.1.9' => [
                'subject' => 'Code optimalizations',
                'realese_date' => Carbon::create(2022, 7, 20, 0),
                'description' => 'Optimise core of application and template views.',
                'background' => '',
            ],
            '0.2.0' => [
                'subject' => 'Reporting and analysis',
                'realese_date' => Carbon::create(2022, 8, 16, 0),
                'description' => '',
                'background' => 'indigo',
            ],
            '0.2.2' => [
                'subject' => 'Overview for items',
                'realese_date' => Carbon::create(2022, 9, 4, 0),
                'description' => 'For tables, lists and details of items.',
                'background' => '',
            ],
            '0.2.5' => [
                'subject' => 'Overview for dashboard',
                'realese_date' => Carbon::create(2022, 10, 25, 0),
                'description' => 'Add dashboard with overviews for: today, projects, tasks and tickets',
                'background' => '',
            ],
            '0.2.8' => [
                'subject' => 'Complete overviews for app',
                'realese_date' => Carbon::create(2022, 12, 10, 0),
                'description' => '',
                'background' => '',
            ],
        ]);
    }
}
