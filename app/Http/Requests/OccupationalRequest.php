<?php

namespace App\Http\Requests;

use App\Models\CertificateType;
use App\Rules\UniqueCertificateTypePerOrder;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OccupationalRequest extends FormRequest
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
            'order.id' => ['required', 'exists:orders,id', new UniqueCertificateTypePerOrder(CertificateType::OCCUPATIONAL)],
            'order.order_number' => ['required', 'exists:orders,order_number'],
            'doctor.id' => ['required', 'exists:doctors,id'],
            'medical_exam' => ['required', 'array'],
            'medical_exam.copro' => ['required', 'string', 'max:255'],
            'medical_exam.dental_assessment' => ['required', 'string', 'max:255'],
            'medical_exam.psychological_assessment' => ['required', 'string', 'max:255'],
            'medical_exam.extra_tests' => ['string', 'max:255'],
            'medical_exam.ecg' => ['required', 'string', 'max:255'],
            'medical_exam.koh' => ['required', 'string', 'max:255'],
            'medical_exam.vdrl' => ['required', 'string', 'max:255'],
            'medical_exam.other_tests' => ['required', 'array'],
            'medical_exam.other_tests.*.test' => ['required', 'string', 'max:255'],
            'medical_exam.other_tests.*.status' => ['required', 'string', 'in:normal,abnormal'],
            'medical_exam.other_tests.*.detail' => ['string', 'max:255', $this->ruleDetailsRequiredIfAbnormal()],
            'medical_exam.aptitude_eval' => ['required', 'array'],
            'medical_exam.aptitude_eval.corrective_lenses' => ['required', 'boolean'],
            'medical_exam.aptitude_eval.restrictions' => ['required', 'boolean'],
            'medical_exam.aptitude_eval.observations' => ['required', 'string', 'max:255'],
            'medical_exam.aptitude_eval.restriction_description' => [
                'required',
                'string',
                'max:255',
                Rule::requiredIf(function () {
                    $restrictions = $this->input('medical_exam.aptitude_eval.restrictions', false);
                    return isset($restrictions) && $restrictions === true;
                })
            ],
            'medical_exam.aptitude_eval.watchkeeping' => ['required', 'string', 'max:255', 'in:fit,unfit'],
            'medical_exam.aptitude_eval.service_matrix' => ['required', 'array'],
            'medical_exam.aptitude_eval.service_matrix.*.result' => ['required', 'in:fit,unfit'],
            'medical_exam.aptitude_eval.service_matrix.*.service' => ['required', 'string', 'max:255'],
            'medical_exam.clinical_data' => ['required', 'array'],
            'medical_exam.clinical_data.blood_pressure_diastolic' => ['required', 'numeric', 'min:0', 'max:300'],
            'medical_exam.clinical_data.blood_pressure_systolic' => ['required', 'numeric', 'min:0', 'max:300'],
            'medical_exam.clinical_data.blood_type' => ['required', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'medical_exam.clinical_data.emo' => ['required', 'string', 'max:255'],
            'medical_exam.clinical_data.glucose' => ['required', 'string', 'max:255'],
            'medical_exam.clinical_data.height' => ['required', 'numeric', 'min:0'],
            'medical_exam.clinical_data.observations' => ['string', 'max:255'],
            'medical_exam.clinical_data.protein' => ['required', 'string', 'max:255'],
            'medical_exam.clinical_data.pulse' => ['required', 'numeric', 'min:0'],
            'medical_exam.clinical_data.result' => ['required', 'string', 'max:255'],
            'medical_exam.clinical_data.weight' => ['required', 'numeric', 'min:0'],
            'medical_exam.clinical_data.chest_xray.status' => ['required', 'in:was_done,not_done'],
            'medical_exam.clinical_data.chest_xray.date' => [
                'required',
                'date',
                'before_or_equal:today',
                Rule::requiredIf(function () {
                    $chest_xray_status = $this->input('medical_exam.clinical_data.chest_xray.status', 'not_done');
                    return $chest_xray_status === 'was_done';
                })
            ],
            'medical_exam.clinical_data.checks' => ['required', 'array'],
            'medical_exam.clinical_data.checks.*.result' => ['required', 'in:normal,abnormal'],
            'medical_exam.clinical_data.checks.*.test' => ['required', 'string', 'max:255'],
            'medical_exam.declarations' => ['required', 'array'],
            'medical_exam.declarations.*.aclarations' => ['string', 'max:255'],
            'medical_exam.declarations.*.questions.*.test' => ['required', 'string', 'max:255'],
            'medical_exam.declarations.*.questions.*.value' => ['required', 'boolean'],
        ];
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'order.id.required' => 'No tiene asociado una orden, es obligatorio.',
            'order.id.exists' => 'La orden seleccionada no existe.',
            'order.order_number.required' => 'No tiene asociado el número de orden, es obligatorio.',
            'order.order_number.exists' => 'El número de orden no existe.',
            'doctor.id.required' => 'No tiene asociado un médico, es obligatorio.',
            'doctor.id.exists' => 'El médico seleccionado no existe.',
            'medical_exam.required' => 'No ha enviado los resultados del examen médico, es obligatorio.',
            'medical_exam.array' => 'El examen médico debe enviarse como arreglo.',
            'medical_exam.copro.max' => 'El resultado del examen copro no debe exceder los 255 caracteres.',
            'medical_exam.copro.required' => 'No ha enviado el resultado del examen copro, es obligatorio.',
            'medical_exam.dental_assessment.max' => 'El resultado del examen dental no debe exceder los 255 caracteres.',
            'medical_exam.dental_assessment.required' => 'No ha enviado el resultado del examen dental, es obligatorio.',
            'medical_exam.psychological_assessment.max' => 'El resultado del examen psicológico no debe exceder los 255 caracteres.',
            'medical_exam.psychological_assessment.required' => 'No ha enviado el resultado del examen psicológico, es obligatorio.',
            'medical_exam.extra_tests.max' => 'El resultado de los exámenes adicionales no debe exceder los 255 caracteres.',
            'medical_exam.ecg.max' => 'El resultado del examen ECG no debe exceder los 255 caracteres.',
            'medical_exam.ecg.required' => 'No ha enviado el resultado del examen ECG, es obligatorio.',
            'medical_exam.koh.max' => 'El resultado del examen KOH no debe exceder los 255 caracteres.',
            'medical_exam.koh.required' => 'No ha enviado el resultado del examen KOH, es obligatorio.',
            'medical_exam.vdrl.max' => 'El resultado del examen VDRL no debe exceder los 255 caracteres.',
            'medical_exam.vdrl.required' => 'No ha enviado el resultado del examen VDRL, es obligatorio.',
            'medical_exam.other_tests.required' => 'No ha enviado los resultados de otros exámenes, es obligatorio.',
            'medical_exam.other_tests.array' => 'Los resultados de otros exámenes deben ser un arreglo.',
            'medical_exam.other_tests.*.test.required' => 'Cada resultado de otros exámenes debe tener un nombre de prueba, es obligatorio.',
            'medical_exam.other_tests.*.test.max' => 'El nombre de la prueba de otros exámenes no debe exceder los 255 caracteres.',
            'medical_exam.other_tests.*.status.required' => 'Cada resultado de otros exámenes debe tener un estado, es obligatorio.',
            'medical_exam.other_tests.*.status.in' => 'El estado de otros exámenes debe ser "normal" o "abnormal".',
            'medical_exam.other_tests.*.detail.required' => 'Cada resultado de otros exámenes debe tener un detalle si el estado es "abnormal", es obligatorio.',
            'medical_exam.other_tests.*.detail.max' => 'El detalle de otros exámenes no debe exceder los 255 caracteres.',
            'medical_exam.aptitude_eval.required' => 'No ha enviado los resultados de la evaluación de la aptitud para servicio en el mar, es obligatorio.',
            'medical_exam.aptitude_eval.array' => 'Los resultados de la evaluación de la aptitud para servicio en el mar deben ser un arreglo.',
            'medical_exam.aptitude_eval.corrective_lenses.required' => 'No ha enviado la información sobre el uso de lentes correctivos en la evaluación de la aptitud para servicio en el mar, es obligatorio.',
            'medical_exam.aptitude_eval.corrective_lenses.boolean' => 'La información sobre el uso de lentes correctivos en la evaluación de la aptitud para servicio en el mar debe ser un valor booleano.',
            'medical_exam.aptitude_eval.observations.required' => 'No ha enviado las observaciones de la evaluación de la aptitud para servicio en el mar, es obligatorio.',
            'medical_exam.aptitude_eval.observations.max' => 'Las observaciones de la evaluación de la aptitud para servicio en el mar no deben exceder los 255 caracteres.',
            'medical_exam.aptitude_eval.restrictions.required' => 'No ha enviado la información sobre restricciones en la evaluación de la aptitud para servicio en el mar, es obligatorio.',
            'medical_exam.aptitude_eval.restrictions.boolean' => 'La información sobre restricciones en la evaluación de la aptitud para servicio en el mar debe ser un valor booleano.',
            'medical_exam.aptitude_eval.restriction_description.required' => 'No ha enviado la descripción de las restricciones en la evaluación de la aptitud para servicio en el mar, es obligatorio cuando hay restricciones.',
            'medical_exam.aptitude_eval.restriction_description.max' => 'La descripción de las restricciones en la evaluación de la aptitud para servicio en el mar no debe exceder los 255 caracteres.',
            'medical_exam.aptitude_eval.watchkeeping.required' => 'No ha enviado la información sobre la aptitud para el servicio de vigía en la evaluación de la aptitud para servicio en el mar, es obligatorio.',
            'medical_exam.aptitude_eval.watchkeeping.max' => 'La información sobre la aptitud para el servicio de vigía en la evaluación de la aptitud para servicio en el mar no debe exceder los 255 caracteres.',
            'medical_exam.aptitude_eval.watchkeeping.in' => 'La información sobre la aptitud para el servicio de vigía en la evaluación de la aptitud para servicio en el mar debe ser "Apto" o "No apto".',
            'medical_exam.aptitude_eval.service_matrix.required' => 'No ha enviado la matriz de servicios en la evaluación de la aptitud para servicio en el mar, es obligatorio.',
            'medical_exam.aptitude_eval.service_matrix.array' => 'La matriz de servicios en la evaluación de la aptitud para servicio en el mar debe ser un arreglo.',
            'medical_exam.aptitude_eval.service_matrix.*.result.required' => 'Cada resultado en la matriz de servicios en la evaluación de la aptitud para servicio en el mar debe tener un resultado, es obligatorio.',
            'medical_exam.aptitude_eval.service_matrix.*.result.in' => 'Cada resultado en la matriz de servicios en la evaluación de la aptitud para servicio en el mar debe ser "Apto" o "No apto".',
            'medical_exam.aptitude_eval.service_matrix.*.service.required' => 'Cada resultado en la matriz de servicios en la evaluación de la aptitud para servicio en el mar debe tener un nombre de servicio, es obligatorio.',
            'medical_exam.aptitude_eval.service_matrix.*.service.max' => 'El nombre del servicio en la matriz de servicios en la evaluación de la aptitud para servicio en el mar no debe exceder los 255 caracteres.',
            'medical_exam.clinical_data.required' => 'No ha enviado los datos clínicos, es obligatorio.',
            'medical_exam.clinical_data.array' => 'Los datos clínicos deben enviarse como arreglo.',
            'medical_exam.clinical_data.blood_pressure_diastolic.required' => 'La presión diastólica es obligatoria.',
            'medical_exam.clinical_data.blood_pressure_diastolic.numeric' => 'La presión diastólica debe ser numérica.',
            'medical_exam.clinical_data.blood_pressure_diastolic.min' => 'La presión diastólica no puede ser menor que 0.',
            'medical_exam.clinical_data.blood_pressure_diastolic.max' => 'La presión diastólica no puede ser mayor que 300.',
            'medical_exam.clinical_data.blood_pressure_systolic.required' => 'La presión sistólica es obligatoria.',
            'medical_exam.clinical_data.blood_pressure_systolic.numeric' => 'La presión sistólica debe ser numérica.',
            'medical_exam.clinical_data.blood_pressure_systolic.min' => 'La presión sistólica no puede ser menor que 0.',
            'medical_exam.clinical_data.blood_pressure_systolic.max' => 'La presión sistólica no puede ser mayor que 300.',
            'medical_exam.clinical_data.blood_type.required' => 'El tipo de sangre es obligatorio.',
            'medical_exam.clinical_data.blood_type.in' => 'El tipo de sangre seleccionado no es válido.',
            'medical_exam.clinical_data.emo.required' => 'El resultado EMO es obligatorio.',
            'medical_exam.clinical_data.emo.max' => 'El resultado EMO no debe exceder los 255 caracteres.',
            'medical_exam.clinical_data.glucose.required' => 'El resultado de glucosa es obligatorio.',
            'medical_exam.clinical_data.glucose.max' => 'El resultado de glucosa no debe exceder los 255 caracteres.',
            'medical_exam.clinical_data.height.required' => 'La estatura es obligatoria.',
            'medical_exam.clinical_data.height.numeric' => 'La estatura debe ser numérica.',
            'medical_exam.clinical_data.height.min' => 'La estatura no puede ser menor que 0.',
            'medical_exam.clinical_data.observations.max' => 'Las observaciones clínicas no deben exceder los 255 caracteres.',
            'medical_exam.clinical_data.protein.required' => 'El resultado de proteínas es obligatorio.',
            'medical_exam.clinical_data.protein.max' => 'El resultado de proteínas no debe exceder los 255 caracteres.',
            'medical_exam.clinical_data.pulse.required' => 'El pulso es obligatorio.',
            'medical_exam.clinical_data.pulse.numeric' => 'El pulso debe ser numérico.',
            'medical_exam.clinical_data.pulse.min' => 'El pulso no puede ser menor que 0.',
            'medical_exam.clinical_data.result.required' => 'El resultado clínico general es obligatorio.',
            'medical_exam.clinical_data.result.max' => 'El resultado clínico general no debe exceder los 255 caracteres.',
            'medical_exam.clinical_data.weight.required' => 'El peso es obligatorio.',
            'medical_exam.clinical_data.weight.numeric' => 'El peso debe ser numérico.',
            'medical_exam.clinical_data.weight.min' => 'El peso no puede ser menor que 0.',
            'medical_exam.clinical_data.chest_xray.status.required' => 'Debe indicar el estado de la radiografía de tórax.',
            'medical_exam.clinical_data.chest_xray.status.in' => 'El estado de la radiografía de tórax no es válido.',
            'medical_exam.clinical_data.chest_xray.date.required' => 'La fecha de la radiografía de tórax es obligatoria cuando fue realizada.',
            'medical_exam.clinical_data.chest_xray.date.date' => 'La fecha de la radiografía de tórax no tiene un formato válido.',
            'medical_exam.clinical_data.chest_xray.date.before_or_equal' => 'La fecha de la radiografía de tórax no puede ser futura.',
            'medical_exam.clinical_data.checks.required' => 'Debe enviar los chequeos clínicos.',
            'medical_exam.clinical_data.checks.array' => 'Los chequeos clínicos deben enviarse como arreglo.',
            'medical_exam.clinical_data.checks.*.result.required' => 'Cada chequeo clínico debe tener resultado.',
            'medical_exam.clinical_data.checks.*.result.in' => 'El resultado del chequeo clínico debe ser "normal" o "abnormal".',
            'medical_exam.clinical_data.checks.*.test.required' => 'Cada chequeo clínico debe tener nombre de prueba.',
            'medical_exam.clinical_data.checks.*.test.max' => 'El nombre de la prueba clínica no debe exceder los 255 caracteres.',
            'medical_exam.declarations.required' => 'Debe enviar las declaraciones médicas.',
            'medical_exam.declarations.array' => 'Las declaraciones médicas deben enviarse como arreglo.',
            'medical_exam.declarations.*.declaration.max' => 'El texto de la declaración no debe exceder los 255 caracteres.',
            'medical_exam.declarations.*.questions.*.test.required' => 'Cada pregunta de declaración debe incluir el texto de la prueba.',
            'medical_exam.declarations.*.questions.*.test.max' => 'El texto de la prueba en declaraciones no debe exceder los 255 caracteres.',
            'medical_exam.declarations.*.questions.*.value.required' => 'Cada pregunta de declaración debe tener una respuesta.',
            'medical_exam.declarations.*.questions.*.value.boolean' => 'La respuesta de cada pregunta de declaración debe ser verdadero o falso.',
        ];
    }

    private function ruleDetailsRequiredIfAbnormal()
    {
        return Rule::requiredIf(function () {
            $other_tests = $this->input('medical_exam.other_tests', []);
            foreach ($other_tests as $test) {
                if (isset($test['status']) && $test['status'] === 'abnormal') {
                    return true;
                }
            }
            return false;
        });
    }
}
