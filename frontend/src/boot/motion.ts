import { defineBoot } from "@quasar/app-vite/wrappers";
import { MotionPlugin } from "@vueuse/motion";

export default defineBoot(({app}) => {
  app.use(MotionPlugin)
});
