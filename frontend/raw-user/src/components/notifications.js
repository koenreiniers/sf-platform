import Vue from 'vue';
import { http, router } from 'raw';
import { locator } from 'raw/templating';
import $ from 'jquery';

let Component = Vue.extend({
    template: locator.locate('notifications'),
    data: function() {

        var comp = this;

        var url = router.generate('raw_user.api.notification.index');

        http.get(url).then(function(response){

            var notifications = response.data;

            comp.notifications = notifications;
        });

        http.head(router.generate('raw_user.api.notification.unread')).then((response) => {
            this.unreadCount = response.headers.get('X-Count');
        });

        return {
            unreadCount: 0,
            notifications: [],
        };
    },
    methods: {
        markAsRead: function(notification)
        {
            var url = router.generate('raw_user.api.notification.view', {
                id: notification.id,
            });
            http.get(url).then(function(response){
                notification.read = true;
            });
        },
        openNotification: function(notification)
        {


            if(!notification.read) {
                this.markAsRead(notification);
            }

            if(notification.url) {
                window.location.href = notification.url;
            }


        }
    },
});


Vue.component('notifications', Component);

export default Component;