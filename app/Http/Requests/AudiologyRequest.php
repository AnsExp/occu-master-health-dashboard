<?php

namespace App\Http\Requests;

use App\Models\CertificateType;
use App\Rules\UniqueCertificateTypePerOrder;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AudiologyRequest extends FormRequest
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
            'order.id' => ['required', 'exists:orders,id', new UniqueCertificateTypePerOrder(CertificateType::AUDIOLOGY)],
            'order.order_number' => ['required', 'exists:orders,order_number'],
            'doctor.id' => ['required', 'exists:doctors,id'],
            'medical_exam' => ['required', 'array'],
            'medical_exam.hearing.left' => ['required', 'string', 'max:255'],
            'medical_exam.hearing.right' => ['required', 'string', 'max:255'],
            'medical_exam.speech_whisper.left' => ['required', 'string', 'in:Susurro,Normal'],
            'medical_exam.speech_whisper.right' => ['required', 'string', 'in:Susurro,Normal'],
            'medical_exam.ishihara.yellow' => ['required', 'string', 'in:N,CP'],
            'medical_exam.ishihara.green' => ['required', 'string', 'in:N,CP'],
            'medical_exam.ishihara.red' => ['required', 'string', 'in:N,CP'],
            'medical_exam.ishihara.blue' => ['required', 'string', 'in:N,CP'],
        ];
    }

    public function messages()
    {
        return [
            'order.id.required' => 'No tiene asociado una orden, es obligatorio.',
            'order.id.exists' => 'La orden seleccionada no existe.',
            'doctor.id.required' => 'No tiene asociado un médico, es obligatorio.',
            'medical_exam.required' => 'No ha enviado los resultados del examen médico, es obligatorio.',
            'medical_exam.hearing.left.required' => 'No ha indicado el resultado del oído izquierdo, es obligatorio.',
            'medical_exam.hearing.right.required' => 'No ha indicado el resultado del oído derecho, es obligatorio.',
            'medical_exam.speech_whisper.left.required' => 'No ha indicado el resultado del susurro izquierdo, es obligatorio.',
            'medical_exam.speech_whisper.left.in' => 'El resultado del susurro izquierdo debe ser Susurro o Normal.',
            'medical_exam.speech_whisper.right.required' => 'No ha indicado el resultado del susurro derecho, es obligatorio.',
            'medical_exam.speech_whisper.right.in' => 'El resultado del susurro derecho debe ser Susurro o Normal.',
            'medical_exam.ishihara.yellow.required' => 'No ha indicado el resultado del test de Ishihara amarillo, es obligatorio.',
            'medical_exam.ishihara.yellow.in' => 'El resultado del test de Ishihara amarillo debe ser N o CP.',
            'medical_exam.ishihara.green.required' => 'No ha indicado el resultado del test de Ishihara verde, es obligatorio.',
            'medical_exam.ishihara.green.in' => 'El resultado del test de Ishihara verde debe ser N o CP.',
            'medical_exam.ishihara.red.required' => 'No ha indicado el resultado del test de Ishihara rojo, es obligatorio.',
            'medical_exam.ishihara.red.in' => 'El resultado del test de Ishihara rojo debe ser N o CP.',
            'medical_exam.ishihara.blue.required' => 'No ha indicado el resultado del test de Ishihara azul, es obligatorio.',
            'medical_exam.ishihara.blue.in' => 'El resultado del test de Ishihara azul debe ser N o CP.',
        ];
    }
}
