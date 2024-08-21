<?php

namespace App\Livewire;

use Livewire\Component;

class HubungiKamiComponent extends Component
{
    public $nama, $nis, $laporan;
    public function render()
    {
        return view('livewire.hubungi-kami-component');
    }
}
