import { fetchAreas } from "./apiArea";

export const loadAreas = async () => {
  const tableArea = document.getElementById('table-area');
  tableArea.innerHTML = `
    <div class="d-flex justify-content-center mt-4">
       <div class="spinner-border" role="status">
         <span class="visually-hidden">Loading...</span>
       </div>
   </div>
`;

  const response = await fetchAreas();
  tableArea.innerHTML = await response;
}