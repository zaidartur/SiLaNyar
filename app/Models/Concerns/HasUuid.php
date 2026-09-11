<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * Map of foreign key column names to their related Model class.
     */
    protected static array $uuidForeignKeyMap = [
        'id_user' => \App\Models\User::class,
        'diverifikasi_oleh' => \App\Models\User::class,
        'id_instansi' => \App\Models\Instansi::class,
        'id_kategori' => \App\Models\Kategori::class,
        'id_jenis_cairan' => \App\Models\JenisCairan::class,
        'id_form_pengajuan' => \App\Models\FormPengajuan::class,
        'id_pengujian' => \App\Models\Pengujian::class,
        'id_hasil_uji' => \App\Models\HasilUji::class,
        'id_parameter' => \App\Models\ParameterUji::class,
        'id_subkategori' => \App\Models\SubKategori::class,
    ];

    /**
     * Boot the trait to auto-generate UUID for the model and normalize foreign keys.
     */
    protected static function bootHasUuid(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });

        static::saving(function ($model) {
            $model->normalizeUuidForeignKeys();
        });
    }

    /**
     * Get the route key for the model (Strict UUID for route model binding).
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Scope to find a record by UUID or numeric ID.
     */
    public function scopeWhereUuidOrId($query, $identifier)
    {
        if (is_numeric($identifier)) {
            return $query->where('id', $identifier);
        }

        return $query->where('uuid', $identifier);
    }

    /**
     * Automatically convert numeric foreign keys to their corresponding UUID.
     */
    public function setAttribute($key, $value)
    {
        if (isset(static::$uuidForeignKeyMap[$key])) {
            $value = $this->resolveUuidForForeignKey($key, $value);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Normalize all registered foreign key columns before saving.
     */
    public function normalizeUuidForeignKeys(): void
    {
        foreach (static::$uuidForeignKeyMap as $column => $relatedClass) {
            if (isset($this->attributes[$column])) {
                $this->attributes[$column] = $this->resolveUuidForForeignKey($column, $this->attributes[$column]);
            }
        }
    }

    /**
     * Resolve a foreign key value to UUID if given a model or numeric ID.
     */
    protected function resolveUuidForForeignKey(string $column, mixed $value): mixed
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof Model) {
            return $value->uuid ?? $value->getKey();
        }

        if (is_numeric($value)) {
            $relatedClass = static::$uuidForeignKeyMap[$column] ?? null;
            if ($relatedClass) {
                $uuid = $relatedClass::where('id', $value)->value('uuid');
                if ($uuid) {
                    return $uuid;
                }
            }
        }

        return $value;
    }

    /**
     * Instantiate a new BelongsToMany relationship that automatically resolves numeric IDs to UUIDs.
     */
    protected function newBelongsToMany(
        Builder $query,
        Model $parent,
        $table,
        $foreignPivotKey,
        $relatedPivotKey,
        $parentKey,
        $relatedKey,
        $relationName = null
    ) {
        return new class($query, $parent, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey, $relationName) extends BelongsToMany {
            protected function parseIds($value)
            {
                $ids = parent::parseIds($value);

                if ($this->relatedKey === 'uuid') {
                    $resolved = [];
                    foreach ($ids as $key => $val) {
                        if (is_array($val)) {
                            $targetKey = is_numeric($key)
                                ? ($this->related->where('id', $key)->value('uuid') ?? $key)
                                : $key;
                            $resolved[$targetKey] = $val;
                        } else {
                            $targetVal = is_numeric($val)
                                ? ($this->related->where('id', $val)->value('uuid') ?? $val)
                                : $val;
                            $resolved[$key] = $targetVal;
                        }
                    }
                    return $resolved;
                }

                return $ids;
            }
        };
    }
}
