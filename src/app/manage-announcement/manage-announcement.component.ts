import { Component, OnInit, Inject } from '@angular/core';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import Swal from 'sweetalert2';
import { DataService } from '../services/data.service';
import { UserService } from '../services/user.service';

@Component({
  selector: 'app-manage-announcement',
  templateUrl: './manage-announcement.component.html',
  styleUrls: ['./manage-announcement.component.scss']
})
export class ManageAnnouncementComponent implements OnInit {
  
  announcement_data: Object = [];
  withimg: number = 0;
  imgdir: string = 'http://localhost/api-gclmsweb/'+this.data.record.an_imgdir;
  editfilepath: string = this.data.record.an_imgdir;
  filepath: string ="";
  // imgdir: string = 'http://localhost/api-gclmsweb/uploads/pikachu.png';
  constructor(private ds: DataService, 
    private user: UserService,
    private dialogRef: MatDialogRef<ManageAnnouncementComponent>, 
    @Inject(MAT_DIALOG_DATA) public data: any) { }

  ngOnInit(): void {
    
  }

  getData(){
    console.log('submit works');
  };

  onClose(){
    this.dialogRef.close({ event: 'close', data: this.announcement_data }); 
  }

  Close(){
    this.dialogRef.close();
  }

  

  
  onSubmit(e){

    let dt = new Object({
      an_announcecode: this.data.record.an_announcecode,
      an_title: e.target[0].value,
      an_content: e.target[1].value,
      an_imgdir: '',
      an_withimg: 0
    });
     

    if(this.data.isEdit == 0){
      dt['an_imgdir'] = this.filepath;
      dt['an_withimg'] = this.withimg;
      this.ds.pull('addannouncement', dt).subscribe((res: any)=>{
       this.announcement_data = res.payload;
        Swal.fire(
          'Good job!',
          'Successfully Posted',
          'success'
        )
        this.onClose();
      }, er=>{
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Something went wrong!',
        })
      }); 
    }
    else{
      dt['an_imgdir'] = this.editfilepath;
      dt['an_withimg'] = 1;
      this.ds.pull('editannouncement', dt).subscribe((res: any)=>{
        console.log(res.payload);
        this.announcement_data = res.payload;
        Swal.fire(
          'Good job!',
          'Edit Successfull',
          'success'
        )
        this.onClose();
      }, er=>{
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Something went wrong!',
        })
      }); 
    }
   
   
    
  }

  file: File = null;


  onUploadFile() {
    const reader = new FileReader();
    reader.onload = () => {
      const formdata = new FormData();
      const imgBlob = new Blob([reader.result], { type: this.file.type });
      formdata.append('file', imgBlob, this.file.name);
      console.log(formdata);
      this.uploadImageData(formdata);
    }
    reader.readAsArrayBuffer(this.file);
  }

  onFileSelect(e) {
    this.file = <File>e.target.files[0];
    this.onUploadFile();
  }

  async uploadImageData(formData: FormData) {
    this.ds.uploadFile('uploadfile', formData).subscribe(res => {
    this.filepath = res['filepath'];
    this.editfilepath = this.filepath;
    this.imgdir = 'http://localhost/api-gclmsweb/'+res['filepath'];
    if(this.data.isEdit==1){
      this.data.record.an_withimg = 1;
      this.data.record.an_imgdir = this.editfilepath;
    }
    else{
      this.withimg = 1;
    }
    
    })
  }

  

}
