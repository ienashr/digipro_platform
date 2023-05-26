<template>
    <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
          <div>
            <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Sign in to your account</h2>
          </div>
          <slot></slot>
        </div>  
    </div>    
</template>
    
    <script>
    export default{
        name: 'GuestLayout',
    }
    </script>
    
    <style scoped>
    </style>