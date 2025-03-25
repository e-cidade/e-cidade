/**
 * Alterna a visibilidade do conteúdo do acordeão em um componente Grid.js.
 *
 * Esta função gerencia o comportamento de expandir/recolher de um elemento
 * de acordeão em um componente Grid.js, controlando a exibição do conteúdo
 * do acordeão e dos ícones associados.
 *
 * @param {Event} event - O objeto de evento acionado pela interação do usuário.
 *                        Espera-se que seja um evento de clique no cabeçalho do acordeão.
 *
 * Funcionalidade:
 * - Alterna a classe CSS "hidden-gridjs-component" no elemento de conteúdo,
 *   exibindo-o ou ocultando-o com base no seu estado atual.
 * - Alterna a visibilidade dos ícones de expandir e recolher ao alternar 
 *   a classe "hidden-gridjs-component" em cada um, proporcionando um feedback 
 *   visual ao usuário sobre o estado do acordeão.
 *
 * Uso:
 * - Anexe esta função como manipulador de eventos ao evento de clique 
 *   do cabeçalho do acordeão em um componente Grid.js para habilitar 
 *   a funcionalidade de expandir/recolher.
 */
function toggleAccordionGridjsComponent(event)
{
    const content = event.currentTarget.nextElementSibling;
    const iconExpand = event.currentTarget.querySelector(".icon-expand");
    const iconCollapse = event.currentTarget.querySelector(".icon-collapse");

    content.classList.toggle("hidden-gridjs-component");

    iconExpand.classList.toggle("hidden-gridjs-component");
    iconCollapse.classList.toggle("hidden-gridjs-component");
}