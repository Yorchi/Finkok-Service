<?php namespace JorgeAndrade;

class FinkokErrors
{
    protected $errorCode;

    public function __construct($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    public function errors($errorCode)
    {
        return [
            201 => "UUID Cancelado exitosamente",
            202 => "UUID Previamente cancelado",
            203 => "UUID No corresponde el RFC del Emisor y de quien solicita la cancelación",
            205 => "UUID No existe",
            300 => "Usuario y contraseña inválidos",
            301 => "XML mal formado",
            302 => "Sello mal formado o inválido",
            303 => "Sello no corresponde a emisor",
            304 => "Certificado Revocado o caduco",
            305 => "La fecha de emisión no esta dentro de la vigencia del CSD del Emisor",
            306 => "El certificado no es de tipo CSD",
            307 => "El CFDI contiene un timbre previo",
            308 => "Certificado no expedido por el SAT",
            401 => "Fecha y hora de generación fuera de rango",
            402 => "RFC del emisor no se encuentra en el régimen de contribuyentes",
            403 => "La fecha de emisión no es posterior al 01 de enero de 2012",
            501 => "Autenticación no válida",
            703 => "Cuenta suspendida",
            704 => "Error con la contraseña de la llave Privada",
            705 => "XML estructura inválida",
            706 => "Socio Inválido",
            707 => "XML ya contiene un nodo TimbreFiscalDigital",
            708 => "No se pudo conectar al SAT",
        ];
    }

    public function message()
    {
        return $this->errors[$this->errorCode];
    }
}
