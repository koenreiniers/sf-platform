// @var Raw
import Vue from 'vue';
import { locator } from 'raw/templating';
import { http, router } from 'raw';

var Widget = Raw.widget('notifications', {
    icon: 'bell-o',
    label: 'Notifications widget',
    component: 'raw-widget-notifications',
    defaultSettings: {
        limit: 10,
        unreadOnly: false,
    },
    settingsForm: {
        limit: {
            type: 'integer',
        },
        unreadOnly: {
            type: 'checkbox',
            required: false,
        },
    },
});

Vue.component('raw-widget-notifications', {
    props: ['widget'],
    template: locator.locate('widgets/notifications'),
    watch: {
        'widget.settings': {
            deep: true,
            handler: function(newValue, oldValue){
                this.update();
            }
        },
    },
    data: function() {

        var self = this;

        var widget = this.widget;



        this.update();

        return {

            notifications: [],
        };
    },
    methods: {
        update: function() {
            var self = this;
            var settings = this.widget.settings;

            var routeName = 'raw_user.api.notification.index';
            if(settings.unreadOnly === true) {
                routeName = 'raw_user.api.notification.unread';
            }

            var url = router.generate(routeName, {
                limit: settings.limit,
            });
            http.get(url).then(function(response){
                self.notifications = response.data;
            });
        },
    },
});

export default Widget;