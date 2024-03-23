<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Edit Data</title>
</head>

<body class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 antialiased">
    @php
    $editDataConfig = [
    'title'=>"Edit Company",
    'actionRoute'=>route('user.update', $user),
    'actionBtn'=>"Submit" ,
    'discardBtn'=>"Discard" ,
    'formmethod'=>'POST',
    'indexRoute'=>route('user.index'),
    'enctype'=>'',
    'user'=>$user,
    ];
    @endphp

    @component('components.modal.editData',$editDataConfig)
    @endcomponent
</body>

</html>