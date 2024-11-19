<?php

namespace App\Livewire;

use App\Models\Attendance;
use Livewire\Component;

class Map extends Component
{
    public function render()
    {
        $attendances = Attendance::with('user')->get();
        return view('livewire.map', [
            'attendances' => $attendances
        ]);
    }
}