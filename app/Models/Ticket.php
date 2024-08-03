<?php

namespace App\Models;

use App\Observers\TicketObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([TicketObserver::class])]
class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'comment',
        'assigned_by',
        'assigned_to',
        'attachment',
        'checklist',
    ];

    protected $casts = [
        'checklist' => 'array',
    ];

    const PRIORITY = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
    ];


    const STATUS = [
        'open' => 'Open',
        'closed' => 'Closed',
        'solved' => 'Solved',
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

}
