import { ref } from 'vue';
import _ from 'lodash';

export default (params={}) => {
  const conf = useRuntimeConfig();

  params = _.merge({
    events: [],
  }, params);

  const pusher = new Pusher(conf.public.PUSHER_APP_KEY, {
    cluster: conf.public.PUSHER_APP_CLUSTER,
    wsHost: conf.public.PUSHER_HOST,
    wsPort: conf.public.PUSHER_PORT,
    wssPort: conf.public.PUSHER_PORT,
    encrypted: conf.public.PUSHER_SCHEME=='https',
    forceTLS: conf.public.PUSHER_SCHEME=='https',
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    scheme: conf.public.PUSHER_SCHEME === 'https' ? 'wss' : 'ws',
  });

  let channels = {};
  let data = {};

  params.events.map((args) => {
    let channelName=null, eventName=null, callback=null;

    if (args.length==2) {
      [ channelName, eventName ] = args[0].split('@');
      callback = args[1];
    }
    else if (args.length==3) {
      [ channelName, eventName, callback ] = args;
    }

    if (channelName && eventName && typeof callback=='function') {
      if (typeof channels[channelName] == 'undefined') {
        channels[channelName] = pusher.subscribe(channelName);
      }

      data[channelName] = data[channelName] || {};
      data[channelName][eventName] = false;

      channels[channelName].bind(eventName, (data) => {
        r.value[channelName][eventName] = data || false;
        callback(data);
      });
    }
  });

  const r = ref({
    ...data,
    trigger(channelName, eventName, data) {
      channel.trigger(eventName, data);
    },
  });

  return r;
};
