export default function ErrorHandler(error){
    if(error.status == 422){
        return Object.values(error.data.errors).map(ele=>ele[0]);
      }else {
       return [error.data.message]
      }
}