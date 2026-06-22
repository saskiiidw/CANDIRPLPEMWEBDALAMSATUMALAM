<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class StudentHome extends Component
{
    public string $search = '';
    public string $selectedCategory = 'All';

    public function selectCategory(string $category): void
    {
        $this->selectedCategory = $category;
    }

    public function render()
    {
        $query = User::where('role', 'penjual')
            ->where('is_verified', true)
            ->where('is_active', true);

        // Filter based on search query
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('store_name', 'like', '%' . $this->search . '%')
                  ->orWhere('name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('menus', function ($mq) {
                      $mq->where('name', 'like', '%' . $this->search . '%')
                         ->orWhere('description', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filter based on category pill
        if ($this->selectedCategory !== 'All') {
            $categoryMap = [
                'Breakfast' => ['makanan_berat', 'breakfast'],
                'Lunch' => ['makanan_berat', 'lunch'],
                'Snacks' => ['makanan_ringan', 'snacks'],
                'Drinks' => ['minuman', 'drinks'],
            ];

            if (isset($categoryMap[$this->selectedCategory])) {
                $categories = $categoryMap[$this->selectedCategory];
                $query->whereHas('menus', function ($mq) use ($categories) {
                    $mq->whereIn('category', $categories)
                       ->where('is_active', true);
                });
            }
        }

        $canteens = $query->get()->map(function ($canteen) {
            // Add virtual properties for UI styling matching the design
            $canteen->rating = round(4.0 + (crc32($canteen->email) % 10) / 10, 1);
            $canteen->distance = round(0.1 + (crc32($canteen->store_name) % 9) / 10, 1) . ' miles';
            $canteen->is_open = true;
            $canteen->description = $canteen->description ?? 'Healthy & delicious campus meals';
            return $canteen;
        });

        return view('livewire.student-home', [
            'canteens' => $canteens
        ]);
    }
}
