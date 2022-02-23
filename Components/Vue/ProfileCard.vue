<template>
  <div class='d-flex justify-content-end' style="width:100%">
    <template v-if='Object.keys(data).length > 0'>
      <div style="width:200px;">
        <dia-button :params="{
          button: 'primary',
          title: 'Oznámenia',
          icon: 'far fa-bell',
          modalComponentToRender: 'dia-messages',
          modalComponentToRenderParams: {
            tableName: 'dia_notifications',
            data: [],
            conditions: [],
            customActions: {
              loadData: 'dia_get_notifications',
              editUrl: 'url',
              afterEdit: 'dia_update_notifications_viewed'
            }
          }
        }"></dia-button>
      </div>
      <div class="ml-5" style="width:200px;">
        <dia-button :params="{
          button: 'secondary',
          title: 'Správy',
          icon: 'far fa-envelope',
          modalComponentToRender: 'dia-messages',
          modalComponentToRenderParams: {
            tableName: 'dia_messages',
            data: [],
            conditions: [],
            customActions: {
              loadData: 'dia_get_messages',
              insertData: 'dia_post_message_answer',
              editData: 'dia_get_message_answer'
            }
          }
        }"></dia-button>
      </div>
      <div class="row ml-5 pl-5" style="width:200px">
        <a :href="url">
          <div class='header-icon'>
            <img 
              :src="'http://localhost/'+ this.dir +'/files/'  + this.fileDir + '/' + checkImage(data['avatar'])" 
              alt='profile' 
            >
          </div>
        </a>
        <a :href="url" class="profile-name">
          <p class="profile-name">{{ data.first_name }}</p>
        </a>
        <div class='profile-logout'>
          <i @click="logout()" class="fas fa-sign-out-alt"></i>
        </div>
      </div>
    </template>
    <template v-else>
      <a href='login' class="btn mb-2 mb-md-0 btn-grey btn-block"><span>Prihlásiť</span> 
        <div class="icon d-flex align-items-center justify-content-center">
          <i class="fas fa-sign-in-alt"></i>
        </div>
      </a>
    </template>
  </div>
</template>

<script>
import diaButton from './Button.vue';

var diaProfileCard = Object();

export default {
  components: {
    'dia-button': diaButton
  },
  props: ['params'],
  data() {
    return Object.assign(diaProfileCard, {
    })
  },
  methods: {
    logout() {
      axios.post('index.php?action=dia_user_logout', {
        params: {
          logout: true
        }
      }).then((res) => {
        window.location.href = res.data;
      })
    },
    checkImage(image) {
      return diaTables.checkImage(image);
    }
  },
  beforeCreate() {
    diaProfileCard = new Dia();
  },
  beforeMount() {
    f.setComponentParams(this);
    f.initComponent(this, false, 'dia_logged_user');

    this.url = this.params['url'];
  },
}
</script>