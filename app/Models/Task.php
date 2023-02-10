<?php

namespace App\Models;

use App\Enums\Priority;
use Illuminate\Database\Eloquent\{
    Concerns\HasUuids,
    Factories\HasFactory,
    Model,
    Relations\BelongsTo,
    Relations\HasMany,
    SoftDeletes,
    Builder
};
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Searchable;
use Spatie\EloquentSortable\SortableTrait;

class Task extends Model
{
    use HasFactory, HasUuids, SoftDeletes, SortableTrait, Searchable;


    protected $fillable = [
        'title',
        'content',
    ];

    protected $casts = [
        'priority' => Priority::class,
        'completed_at' => 'datetime',
        'due_date' => 'datetime',
    ];

    public array $sortable = [
        'order_column_name' => 'order_column',
        'sort_when_creating' => true,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOnlyRoot(Builder $query)
    {
        return $query->whereNull('parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }


    public function scopeCompleted(Builder $query): Builder
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->whereNull('completed_at');
    }


    #[SearchUsingFullText(['content'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
        ];
    }
}
