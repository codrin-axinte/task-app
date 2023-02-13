<?php

namespace App\Enums;

enum TaskFilter: string
{
    case All = 'all';
    case Pending = 'pending';
    case Completed = 'completed';
    case Trashed = 'trashed';
}
