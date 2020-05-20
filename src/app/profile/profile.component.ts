import { Component, OnInit, Inject } from '@angular/core';
import { DataService } from '../services/data.service';
import { UserService } from '../services/user.service';
import { StudentInfoData, f } from '../data-schema';
import {MatDialog, MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';

export interface DialogData {
  animal: string;
  name: string;
}

@Component({
  selector: 'dialog-overview-example-dialog',
  templateUrl: 'editpassword.html',
})
// Modal
export class DialogOverviewExampleDialog {
  pass = new f;
  constructor(
    public dialogRef: MatDialogRef<DialogOverviewExampleDialog>,
    @Inject(MAT_DIALOG_DATA) public data: DialogData, private ds:DataService, private user:UserService) {}

  onNoClick(): void {
    this.dialogRef.close();
  }

  savepass(e){
    e.preventDefault();
    if(e.target[1].value === e.target[2].value){
      this.pass.studnum = this.user.getUserID();
      this.pass.s_oldpass = e.target[0].value;
      this.pass.s_newpass = e.target[1].value;
      this.ds.pull('changestudentpass',this.pass).subscribe((res: any)=>{
        console.log("password change");
        this.dialogRef.close();
      })
    }
  }
  
}
// Main
@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.scss']
})
export class ProfileComponent implements OnInit {

  v_image: string = "";
  constructor(private ds: DataService, private user:UserService, private dialog:MatDialog) { }
  student_obj: Object = [];
  val = new StudentInfoData();

  isEditable: boolean = false;

  ngOnInit(): void {
    this.getData();
  }

  onClick(){
    this.dialog.open(DialogOverviewExampleDialog);
  }

  getData(){
    this.ds.pull('studentinfo/' + '201910734', '').subscribe((res: any) => {
      this.student_obj = res.payload;
      this.v_image = this.student_obj[0].s_img;
    }, er => {
      console.log(er);
      
    });
  }
  edit(){
    this.isEditable = !this.isEditable;
  }

  save(e) {
    e.preventDefault();
    this.val.s_studnum = this.user.getUserID();
    this.val.s_contactnum = e.target[2].value;
    this.val.s_email = e.target[1].value;
    this.val.s_img = this.v_image;
    this.ds.pull('editstudent', this.val).subscribe((res: any) => {
      this.isEditable = !this.isEditable;
      this.student_obj = res.payload;

    });

  }

  file: File = null;

  onUploadFile() {
    const reader = new FileReader();
    reader.onload = () => {
      const formdata = new FormData();
      const imgBlob = new Blob([reader.result], { type: this.file.type });
      formdata.append('file', imgBlob, this.file.name);
      this.uploadImageData(formdata);
    }
    reader.readAsArrayBuffer(this.file);
  }

  onFileSelect(e) {
    this.file = <File>e.target.files[0];
    this.onUploadFile();
  }

  async uploadImageData(formData: FormData) {
    this.ds.uploadFile('uploadfile/'+this.user.getUserID(), formData).subscribe(res => {
      this.v_image = res['filepath'];
    })
  }
  
}
