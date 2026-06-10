<?php

namespace App\Http\Requests;

use App\Models\CertificateType;
use App\Rules\UniqueCertificateTypePerOrder;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OphthalmologyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order.id' => ['required', 'exists:orders,id', new UniqueCertificateTypePerOrder(CertificateType::OPHTHALMOLOGY)],
            'order.order_number' => ['required', 'exists:orders,order_number'],
            'doctor.id' => ['required', 'exists:doctors,id'],
            'medical_exam' => ['required', 'array'],
            'medical_exam.visual_field' => ['required', 'array'],
            'medical_exam.visual_acuity' => ['required', 'array'],
            'medical_exam.corrective_lenses' => ['required', 'array'],
            'medical_exam.corrective_lenses.usage' => ['required', 'in:No usa,Anteojos,Lentes de contacto,Ambos'],
            'medical_exam.corrective_lenses.function' => ['string', 'max:255', Rule::requiredIf(function () {
                $corrective_lenses_usage = $this->input('medical_exam.corrective_lenses.usage');
                return in_array($corrective_lenses_usage, ['Anteojos', 'Lentes de contacto', 'Ambos']);
            })],
            'medical_exam.visual_acuity.*.*.*' => ['required', 'string', 'regex:/^(20|6)\/([1-9][0-9]*)$/'],
            'medical_exam.visual_field.*' => ['required', 'in:Normal,Defectuosa'],
            'medical_exam.color_vision' => ['required', 'string', 'in:Sin probar,Dudosa,Normal,Defectuosa'],
        ];
    }

    public function messages()
    {
        return [
            'order.id.required' => 'No tiene asociado una orden, es obligatorio.',
            'order.id.exists' => 'La orden seleccionada no existe.',
            'doctor.id.required' => 'No tiene asociado un médico, es obligatorio.',
            'medical_exam.required' => 'No ha enviado los resultados del examen médico, es obligatorio.',
            'medical_exam.visual_acuity.required' => 'No ha indicado el resultado de la agudeza visual, es obligatorio.',
            'medical_exam.visual_acuity.*.*.*.required' => 'No ha indicado el resultado de la agudeza visual para uno de los ojos, es obligatorio.',
            'medical_exam.visual_acuity.*.*.*.regex' => 'El resultado de la agudeza visual debe tener el formato 20/XX o 6/XX, donde XX es un número mayor que 0.',
            'medical_exam.visual_field.required' => 'No ha indicado el resultado del campo visual, es obligatorio.',
            'medical_exam.visual_field.*.required' => 'No ha indicado el resultado del campo visual para uno de los ojos, es obligatorio.',
            'medical_exam.visual_field.*.in' => 'El resultado del campo visual debe ser Normal o Defectuosa.',
            'medical_exam.color_vision.required' => 'No ha indicado el resultado del test de visión de colores, es obligatorio.',
            'medical_exam.color_vision.in' => 'El resultado del test de visión de colores debe ser Sin probar, Dudosa, Normal o Defectuosa.',
            'medical_exam.corrective_lenses.usage.required' => 'No ha indicado si usa lentes correctivos, es obligatorio.',
            'medical_exam.corrective_lenses.usage.in' => 'La indicación sobre el uso de lentes correctivos debe ser No usa, Anteojos, Lentes de contacto o Ambos.',
            'medical_exam.corrective_lenses.function.required' => 'No ha indicado la función de los lentes correctivos, es obligatorio.',
        ];
    }
}
