import { startStimulusApp } from '@symfony/stimulus-bridge';
import AnswerVoteController from "./controllers/answer-vote_controller";

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.(j|t)sx?$/
));

// register any custom, 3rd party controllers here
// app.register('answer-vote', AnswerVoteController);
