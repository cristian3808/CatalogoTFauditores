<?php
require "validarAcceso.php";
require_once "config/conexion.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="assets/estaticas/TF.ico" type="image/x-icon">
    <title>TF Acceso</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            background-color: #E1EEE2;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto flex items-center justify-center p-4">
            <a href="https://tfauditores.com/">
                <img src="/assets/estaticas/TF.png" alt="Logo-TF" class="h-20">
            </a>
        </div>
    </header>

    <main class="flex h-screen justify-center items-center">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h5 class="text-xl font-semibold text-center mb-4">Ingrese el código de acceso</h5>
            <form action="validarAcceso.php" method="POST">
                <div class="mb-4">
                    <div class="flex items-center border rounded-lg overflow-hidden">
                        <input type="password" name="accessCode" id="accessCode" placeholder="Código del proyecto" class="w-full p-2 border-none focus:ring-0 focus:outline-none" maxlength="8" required>
                        <button type="button" id="togglePassword" class="px-3 text-gray-500">
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>
                <button type="submit" class="w-full bg-green-600 hover:bg-lime-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg transform transition hover:scale-105">
                    INGRESAR
                </button>
            </form>
        </div>
    </main>

    <footer class="bg-white text-gray-600 body-font fixed bottom-0 w-full">
    <div class="container px-5 py-8 mx-auto flex items-center sm:flex-row flex-col">
                <p id="year" class="text-sm text-gray-700 sm:ml-4 sm:pl-4 sm:border-l-2 sm:border-gray-200 sm:py-2 sm:mt-0 mt-4">© <span id="current-year"></span> TF AUDITORES</p>
                <span class="inline-flex sm:ml-auto sm:mt-0 mt-4 justify-center sm:justify-start">
                    <a href="https://www.facebook.com/people/TF-Auditores-y-Asesores-SAS-BIC/100065088457000/" class="text-gray-700 hover:text-blue-500">
                        <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/tfauditores/" class="ml-3 text-gray-700 hover:text-pink-500">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                            <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path>
                        </svg>
                    </a>
                    <a href="https://www.linkedin.com/uas/login?session_redirect=https%3A%2F%2Fwww.linkedin.com%2Fcompany%2F10364571%2Fadmin%2Fdashboard%2F" class="ml-3 text-gray-700 hover:text-blue-300">
                        <svg fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0" class="w-5 h-5" viewBox="0 0 24 24">
                            <path stroke="none" d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"></path>
                            <circle cx="4" cy="4" r="2" stroke="none"></circle>
                        </svg>
                    </a>
                </span>
            </div>
    </footer>
<script src="/assets/js/index.js"></script>
</body>
</html>
