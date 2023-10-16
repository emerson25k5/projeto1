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


                        // Função para adicionar e remover as barras automaticamente e limitar ao formato "00/00/0000"
                        // Função para adicionar e remover as barras automaticamente e validar a data
                        function formatarData(input) {
                            var value = input.value.replace(/\D/g, ''); // Remove todos os caracteres não numéricos
                            if (value.length > 8) {
                                value = value.substring(0, 8); // Limita a 8 caracteres
                            }
                            if (value.length > 2) {
                                value = value.substring(0, 2) + '/' + value.substring(2);
                            }
                            if (value.length > 5) {
                                value = value.substring(0, 5) + '/' + value.substring(5);
                            }
                            input.value = value;

                            // Validar a data
                            var parts = value.split('/');
                            if (parts.length === 3) {
                                var dia = parseInt(parts[0], 10);
                                var mes = parseInt(parts[1], 10);
                                var ano = parseInt(parts[2], 10);

                                if (
                                    dia < 1 || dia > 31 ||
                                    mes < 1 || mes > 12 ||
                                    ano < 1900 || ano > 2099
                                ) {
                                    input.setCustomValidity('Data inválida');
                                } else {
                                    input.setCustomValidity('');
                                }
                            } else {
                                input.setCustomValidity('Formato inválido');
                            }
                        }

                        // Função para exibir a mensagem de erro abaixo da input de data
                        function exibirMensagemErro(input, mensagem) {
                            var divErro = input.nextElementSibling; // Obtém o próximo elemento, que deve ser a div de erro
                            if (divErro && divErro.className === 'erro-data') {
                                divErro.innerHTML = mensagem; // Define o conteúdo da mensagem de erro
                            } else {
                                divErro = document.createElement('div');
                                divErro.className = 'erro-data';
                                divErro.innerHTML = mensagem;
                                input.parentNode.appendChild(divErro);
                            }
                        }

                        //multiplo select 
                        document.addEventListener('DOMContentLoaded', function() {
                        var elems = document.querySelectorAll('select');
                        var instances = M.FormSelect.init(elems, options);
                    });

</script>