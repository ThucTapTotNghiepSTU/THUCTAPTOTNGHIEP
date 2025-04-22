<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ClassCard extends Component
{
    public $image;
    public $title;
    public $description;
    public $author;
    public $student;
    public $date;

    public function __construct($image, $title, $description, $author, $student, $date)
    {
        $this->image = $image;
        $this->title = $title;
        $this->description = $description;
        $this->author = $author;
        $this->student = $student;
        $this->date = $date;
    }

    public function render(): Factory|View
    {
        return view('components.class-card');
    }
}
