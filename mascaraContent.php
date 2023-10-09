<script>
// Márcara para o CPF
                            document.addEventListener('DOMContentLoaded', function() {
                                const cpfInput = document.getElementById('cpfInput');

                                cpfInput.addEventListener('input', function() {
                                    let value = cpfInput.value.replace(/\D/g, ''); 
                                    if (value.length > 11) {
                                        value = value.slice(0, 11);
                                    }
                                    value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                                    cpfInput.value = value;
                                });
                            });

                                // Márcara para o telefone
                            function formatarTelefone(input) {
                                let numero = input.value.replace(/\D/g, '');
                                if (numero.length === 11) {
                                    input.value = numero.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                                } else if (numero.length === 10) {
                                    input.value = numero.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                                } else {
                                    input.value = numero;
                                }
                            }

                            // Márcara para o cep
                            function formatarCEP(input) {
                            let cep = input.value.replace(/\D/g, '');
                            if (cep.length === 8) {
                                input.value = cep.replace(/(\d{5})(\d{3})/, '$1-$2');
                            } else {
                                input.value = cep;
                            }
                        }



                        // Márcara para o RG
                        function formatarRG(input) {
                        // Remove todos os caracteres não numéricos do valor do campo
                        let rg = input.value.replace(/\D/g, '');

                        // Verifica o comprimento do RG para aplicar a máscara adequada
                        if (rg.length <= 8) {
                            input.value = rg.replace(/(\d{2})(\d{3})(\d{3})/, '$1.$2.$3');
                        } else if (rg.length === 9) {
                            input.value = rg.replace(/(\d{2})(\d{3})(\d{3})(\d{1})/, '$1.$2.$3-$4');
                        } else {
                            // Se o RG não estiver completo, não aplique a máscara
                            input.value = rg;
                        }
                    }


                            //caixa alta
                            function converterParaCaixaAlta(input) {
                                input.value = input.value.toUpperCase();
                            }


                            //inicializar o text area do materialize
                            $(document).ready(function() {
                            $('input#input_text, textarea#textarea2').characterCounter();
                        });

                            //inicializar o select do materialize
                            document.addEventListener('DOMContentLoaded', function() {
                                    var elems = document.querySelectorAll('select');
                                    var instances = M.FormSelect.init(elems);
                                });


                        //Função para adicionar as barras nos inputs de datas
                        function formatarData(input) {
                            var value = input.value.replace(/\D/g, '');
                            if (value.length === 10) {
                                input.value = value.substring(0, 2) + '/' + value.substring(2, 4) + '/' + value.substring(4, 8);
                            } else {
                                input.value = value.substring(0, 2) + '/' + value.substring(2, 4) + '/' + value.substring(4, 10);
                            }
                        }

                        //multiplo select 
                        document.addEventListener('DOMContentLoaded', function() {
                        var elems = document.querySelectorAll('select');
                        var instances = M.FormSelect.init(elems, options);
                    });

</script>