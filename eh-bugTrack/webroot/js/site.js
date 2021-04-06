$(function () {

    //формирование ссылки для фильтрации задач
    function makeSelectorLink(caller){

        //получаем ссылку селектора задач по отношению к пользователю
        let mainLink = $('.list-group-tasks .list-group-item.active').prop('href');

        //если нет активного селектора, то берем URL без фильтров
        if(mainLink == undefined){
            mainLink = '/tasks';
        }

        //достраиваем ссылку из прочих фильтров
        const selectors = ['status','type'];

        selectors.forEach((el) => {

            let elVal = $('#'+el).val();

            if(elVal !== undefined){
                if(mainLink.indexOf('?') > -1){
                    mainLink += '&';
                }
                else {
                    mainLink += '?';
                }

                mainLink += el + '=' + elVal;
            }
        });

        return mainLink;
    }

    //обработка смены селектора статуса задачи
    $('.site-sidebar #status').change(function (){
        document.location.href = makeSelectorLink('status');
    });

    //обработка смены селектора типа задачи
    $('.site-sidebar #type').change(function (){
        document.location.href = makeSelectorLink('type');
    });

})
