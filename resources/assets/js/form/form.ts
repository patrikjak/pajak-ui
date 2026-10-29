import { PajakCheckbox } from './checkbox';
import { PajakCheckboxGroup } from './checkbox-group';
import { PajakColorPicker } from './color-picker';
import { PajakDropzone, PajakAvatar, PajakImageGrid } from './file-upload';
import { PajakFile } from './file';
import { PajakOtp } from './otp';
import { PajakPassword } from './password';
import { PajakRepeater } from './repeater';
import { PajakSelect } from './select';
import { PajakSlider } from './slider';
import { PajakToggle } from './toggle';
import { initFormSubmitLoaders, PajakForm, PajakButton } from './form-submit';
import { PajakToast } from '../toast/toast';

export { PajakCheckbox } from './checkbox';
export { PajakCheckboxGroup } from './checkbox-group';
export { PajakColorPicker } from './color-picker';
export { PajakDropzone, PajakAvatar, PajakImageGrid } from './file-upload';
export { PajakFile } from './file';
export { PajakOtp } from './otp';
export { PajakPassword } from './password';
export { PajakRepeater } from './repeater';
export { PajakSelect } from './select';
export { PajakSlider } from './slider';
export { PajakToggle } from './toggle';
export { initFormSubmitLoaders, PajakForm, PajakButton } from './form-submit';
export { PajakToast } from '../toast/toast';
export type { ToastOptions } from '../toast/toast';

function initAll(root: ParentNode = document): void {
    PajakSelect.initAll(root);
    PajakToggle.initAll(root);
    PajakCheckbox.initAll(root);
    PajakCheckboxGroup.initAll(root);
    PajakColorPicker.initAll(root);
    PajakFile.initAll(root);
    PajakOtp.initAll(root);
    PajakPassword.initAll(root);
    PajakRepeater.initAll(root);
    PajakSlider.initAll(root);
    PajakDropzone.initAll(root);
    PajakAvatar.initAll(root);
    PajakImageGrid.initAll(root);
    initFormSubmitLoaders(root);
}

declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

window.Pajak = { ...window.Pajak, PajakSelect, PajakToggle, PajakCheckbox, PajakCheckboxGroup, PajakColorPicker, PajakFile, PajakOtp, PajakPassword, PajakRepeater, PajakSlider, PajakDropzone, PajakAvatar, PajakImageGrid, PajakForm, PajakButton, PajakToast, initFormSubmitLoaders, initAll };

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => initAll());
} else {
    initAll();
}
