import { Component, OnInit } from '@angular/core';
import { UserService } from '../services/user.service';
import { DataService } from '../services/data.service';
import Swal from 'sweetalert2';
import { TOUCH_BUFFER_MS } from '@angular/cdk/a11y';

@Component({
  selector: 'app-classroom',
  templateUrl: './classroom.component.html',
  styleUrls: ['./classroom.component.scss'],
})
export class ClassroomComponent implements OnInit {
  constructor(private user: UserService, private ds: DataService) { }
  postObject: any;
  subject: Object;
  activities: Object;
  show = 1;
  showpost;
  content: string;
  instructor: boolean;
  student: boolean;
  comments: Object;
  postcode: string;
  pushcomments: string = '';
  error_catcher: string;
  ngOnInit(): void {
    this.getClassPost();
    this.getActivities();
    this.subject = this.user.getClasscode();
  }

  /*#######################################################################################*/
  /*############## NECESARRY FUNCTIONS HERE: MOST NEEDED FUNTIONS ON THE TOP ##############*/
  /*#######################################################################################*/

  /*############# FETCHING FUNTIONS HERE: ##############*/

  // FETCHING CLASSPOST FROM DATABASE:
  getClassPost() {
    this.ds.pull('classpost/' + this.user.getClasscode()[0], null).subscribe((data: any) => {
      this.postObject = data.payload;
      for (let i = 0; i < this.postObject.length; i++) {
        let postcode = this.postObject[i].cp_postcode;
        this.ds.pull('classcomments/' + postcode, null).subscribe((data: any) => {
          this.postObject[i].postcomments = data.payload;
        }, er => {
          console.log(er.error.status.message);
        });
      }
    });
  }

  // FETCH POSTED ACTIVITIES IN DATABASE:
  getActivities() {
    this.ds.pull('classactivity/' + this.user.getClasscode()[0], null).subscribe((data: any) => {
      this.activities = data.payload;
    });
  }

  /*############# PUSHING FUNTIONS HERE: ##############*/

  // SHARING SOME POST IN DATABASE:
  share() {
    let f = new Object({
      cp_studnum: this.user.getUserID(),
      cp_classcode: this.subject[0],
      cp_content: this.content,
    });
    this.ds.pull('addclasspost', f).subscribe((data: any) => {
      this.postObject = data.payload;
      this.cancel();
    });
  }

  pushComment(i, e) {
    e.preventDefault();
    let f = e.target.elements;
    let comments = f.commentbox.value;
    let pc = new Object({
      cc_commentcode: '',
      cc_content: comments,
      cc_postcode: this.postObject[i].cp_postcode,
      cc_studnum: this.user.getUserID(),
    });

    this.ds.pull('addcommentclasspost', pc).subscribe((res: any) => {
      this.postObject[i].postcomments = res.payload;
      f.commentbox.value = "";
    });
  }

  onOpenFile() {
    console.log('Open Window Fired!');
  }

  /*#######################################################################################*/
  /*####### END OF NECESARRY FUNCTIONS HERE: MOST NEEDED FUNTIONS ON THE TOP ##############*/
  /*#######################################################################################*/

  // ALERTS FOR COPIED TO CLIP-BOARD ON CLASSCODE:
  copycode() {
    Swal.fire({
      icon: 'success',
      title: 'Copied to Clipboard',
    });
  }

  // TOGGLE BUTTONS:
  postActivity() {
    this.show = 0;
    this.showpost = 1;
  }
  cancel() {
    this.show = 1;
    this.showpost = 0;
  }
  // END OF TOGGLE BUTTONS:
}
