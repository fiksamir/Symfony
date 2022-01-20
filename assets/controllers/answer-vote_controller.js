import {Controller} from "stimulus";
import axios from 'axios';

export default class extends Controller {
    static targets = ['voteTotal'];
    static values = {
        url: String,
    }

    clickVote(evt) {
        evt.preventDefault();
        const button = evt.currentTarget;
        axios.post(this.urlValue, {
            direction: button.value,
        })
            .then((res) => {
                this.voteTotalTarget.innerHTML = res.data.votes;
            })
        ;
    }
}