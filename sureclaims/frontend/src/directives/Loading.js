import Vue from 'vue';
import Spinner from '../components/spinners/CubeGridSpinner';

Vue.directive('loading', {
  bind(el, binding) {
    /* eslint-disable no-param-reassign */
    const defaultOption = {
      text: 'Loading ...',
      bg: 'rgba(255, 255, 255, 0.8)',
      textColor: '#444',
    };
    const options = Object.assign(defaultOption, {});
    const position = window.getComputedStyle(el).position;
    if (position === 'static' || position === '') {
      el.style.position = 'relative';
    }
    // Message Box Create
    const msg = document.createElement('div');
    // const spinner = document.createElement('i');
    // spinner.className = options.spinnerClass;
    const textContent = document.createElement('p');
    textContent.textContent = options.text;

    const SpinnerClass = Vue.extend(Spinner);
    const spinner = new SpinnerClass({
      propsData: { size: 60 },
    });
    spinner.$mount();

    msg.appendChild(spinner.$el);
    msg.appendChild(textContent);

    // Default Css
    msg.setAttribute('style', 'box-sizing:content-box!important;position:absolute;z-index:1001;margin:0px;padding:0 35px;height:60px;top:20%;left:50%;text-align:center;font-size:1.5rem;line-height:1;cursor:wait;background-color:transparent;border-radius:0;box-shadow:none;border:0;transform:translateX(-50%);');
    msg.style.color = options.textColor;
    const box = document.createElement('div');
    // Default Css
    box.setAttribute('style', 'position:absolute;top:0px;left:0px;z-index:1000;margin:0px;padding:0px;width:100%;height:100%;border:none;background-color:rgba(230,233,236,0.8);cursor:wait;opacity:0;transition:opacity.4s;');
    box.style.backgroundColor = options.bg;
    box.style.display = 'none';
    box.className = 'vue2-loading-box';
    box.appendChild(msg);
    el.appendChild(box);

    if (binding.value) {
      binding.def.showLoadingBox(box);
    } else {
      binding.def.hideLoadingBox(box);
    }
  },

  update(el, binding) {
    const selector = el.getElementsByClassName('vue2-loading-box');
    const box = selector[selector.length - 1];
    if (binding.oldValue !== binding.value) {
      // Mutated State
      if (binding.value) {
        binding.def.showLoadingBox(box);
      } else {
        binding.def.hideLoadingBox(box);
      }
    }
  },

  showLoadingBox(box) {
    box.style.display = 'initial';
    window.requestAnimationFrame(() => {
      box.style.opacity = 1;
    });
  },

  hideLoadingBox(box) {
    box.style.display = 'none';
    window.requestAnimationFrame(() => {
      box.style.opacity = 0;
    });
  },
});
