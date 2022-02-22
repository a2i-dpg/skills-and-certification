<?php

namespace App\Models;

use App\Traits\ScopeAclTrait;
use App\Traits\ScopeRowStatusTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Examination
 * @package App\Models
 * @property string code
 * @property int institute_id
 * @property int batch_id
 * @property int training_center_id
 * @property int examination_type_id
 * @property int pass_mark
 * @property int total_mark
 * @property int status
 * @property int row_status
 * @property int created_by
 * @property int updated_by
 * @property string|null exam_details
 * @property mixed ExaminationResult $examinationResult
 * @method static \Illuminate\Database\Eloquent\Builder|Examination acl()
 * @method static Builder|Examination active()
 * @method static Builder|Examination newModelQuery()
 * @method static Builder|Examination newQuery()
 * @method static Builder|Examination query()
 */
class Examination extends BaseModel
{
    use HasFactory, ScopeAclTrait, ScopeRowStatusTrait;

    const EXAMINATION_STATUS_NOT_PUBLISH = 0;
    const EXAMINATION_STATUS_PUBLISH = 1;
    const EXAMINATION_STATUS_COMPLETE = 2;

    public $timestamps = true;
    protected $guarded = ['id'];

    /**
     * @param $decorated
     * @return array
     */
    public function getExaminationStatusOptions($decorated): array
    {
        return [
            self::EXAMINATION_STATUS_NOT_PUBLISH => sprintf($decorated ? '<span class="badge badge-success"> %s </span>' : '%s', __('admin.examination.examination_not_publish')),
            self::EXAMINATION_STATUS_PUBLISH => sprintf($decorated ? '<span class="badge badge-warning"> %s </span>' : '%s', __('admin.examination.examination_publish')),
            self::EXAMINATION_STATUS_COMPLETE => sprintf($decorated ? '<span class="badge badge-danger"> %s </span>' : '%s', __('admin.examination.examination_complete')),
        ];
    }

    /**
     * get active status of a model
     *
     * @param false $decorated
     * @return string
     */
    public function getCurrentExaminationStatus(bool $decorated = false): string
    {
        $rowStatusArray = $this->getExaminationStatusOptions($decorated);
        if (!empty($rowStatusArray[$this->status])) {
            return $rowStatusArray[$this->status];
        }
        return '';
    }


    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function examinationType(): BelongsTo
    {
        return $this->belongsTo(ExaminationType::class, 'examination_type_id');
    }

    public function trainingCenter(): BelongsTo
    {
        return $this->belongsTo(TrainingCenter::class, 'training_center_id');
    }

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
