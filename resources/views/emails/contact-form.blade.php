<x-mail::message>
# Nuevo Mensaje desde el Sitio Web de Orquestra

Has recibido un nuevo mensaje a través del formulario de contacto.

**Nombre:** {{ $data['name'] }}

**Correo Electrónico:** {{ $data['email'] }}

---

**Mensaje:**

{{ $data['message'] }}

</x-mail::message>