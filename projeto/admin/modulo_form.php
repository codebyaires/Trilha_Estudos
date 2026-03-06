<main class="flex-1 flex flex-col">
        <div class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
                <a href="cursos.php" class="hover:text-senai-blue">Cursos</a> 
                <a href="modulos.php" class="hover:text-senai-blue">Módulos</a>
                <span class="text-gray-700 font-semibold">Editar Módulo</span>
            </div>
            <h1 class="text-xl font-extrabold text-gray-800">Editar Módulo</h1>
        </div>
        <div class="p-6 flex-1 max-w-xl">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <form action="modulos.php" method="post">
                    <input type="hidden" name="id" value="1">
                    <div class="mb-4">
                        <label class="form-label">Curso</label>
                        <select name="curso_id" class="form-input">
                            <option value="1" selected>HTML e CSS do Zero</option>
                            <option value="2">PHP para Iniciantes</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Título do Módulo *</label>
                        <input type="text" name="titulo" class="form-input" value="Introdução ao HTML">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Descrição (opcional)</label>
                        <textarea name="descricao" rows="3" class="form-input resize-none">Fundamentos da linguagem HTML, estrutura de uma página e tags principais.</textarea>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Ordem</label>
                        <input type="number" name="ordem" class="form-input" value="1" min="1">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-senai-blue text-white font-bold px-5 py-2.5 rounded-lg text-sm hover:bg-senai-blue-dark transition">💾 Salvar</button>
                        <a href="modulos.php" class="bg-gray-100 text-gray-600 font-semibold px-5 py-2.5 rounded-lg text-sm hover:bg-gray-200 transition">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
